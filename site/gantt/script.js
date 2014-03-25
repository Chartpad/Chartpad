/// Gantt Drawing
/// Version 5
/// Created By Brian Brewer
/// Last Updated 11/02/2013

function read() {
    $.ajax({
        type: "GET",
        url: "return.php",
        data: {"pid": $("body > span").first().attr("id")},
        success: parseData,
        dataType: "text"
    });
}

function parseData(data) {
    var json = data;
    var pack = JSON.parse(json);

    console.log(pack);

    drawGantt(pack);

    $("#savePNG").bind("click", function (ev) {
        ev.preventDefault();
        saveCanvas("gantt", "png", $("#gantt").get(0));
    });

    $("#saveJPEG").bind("click", function (ev) {
        ev.preventDefault();
        saveCanvas("gantt", "jpg", $("#gantt").get(0));
    });

    $("#saveGIF").bind("click", function (ev) {
        ev.preventDefault();
        saveCanvas("gantt", "gif", $("#gantt").get(0));
    })
}

function drawGantt(data) {
    //.project contains the column for the current project from tblProject
    //.tasks contains all the rows for the current project from tblTask

    //Allows easy navigation / check of returned data

    var tableFontFamily,
        tableFontSize,
        lineWidth,
        padding,
        canvasOffsetX,
        canvasOffsetY,
        canvas,
        context,
        positionX,
        positionY,
        columnName,
        columnDuration,
        columnStartDate,
        columnEndDate,
        columnPredecessor,
        lineHeight,
        maxHeight,
        timeStep,
        timeSpace,
        timeDifference,
        numberOfSteps,
        dateAtPoint,
        dateString,
        columnPair,
        taskParent,
        taskParentCheck,
        predecPair,
        parentID,
        task;

    //Settings
    tableFontFamily = "Calibri";
    tableFontSize = 14;
    tapeFontSize = 10;
    lineWidth = 1;
    padding = 5;
    canvasOffsetX = 0;
    canvasOffsetY = 0;
    
    timeStep = parseInt(data.project.ganttTimeStep, 10) || 7;
    timeSpace = 0;

    //Columns
    // [Fieldname, Displayed Name]
    columnPair = [["taskName", "Task Name"],
        ["taskStartDate", "Start Date"],
        ["taskEndDate", "End Date"]];

    //Gather data
    taskParent = []; //Parents
    predecPair = []; //Predecessor -- Successor Pairs
    for (var i = 0; i < data.tasks.length; i++) {
        task = data.tasks[i];
        if (task.parentID !== null) {
            taskParentCheck = false;
            for (var j = 0; j < taskParent.length; j++) {
                if (taskParent[j] == task.parentID) {
                    taskParentCheck = true;
                }
            };
            if (!taskParentCheck) {
                taskParent.push(task.parentID);
            }
        }
        if(task.taskPredecessor !== null) {
            predecPair.push([task.taskPredecessor, task.taskID]);
        }
    };

    //Canvas
    canvas = document.getElementById("gantt");
    context = canvas.getContext("2d");
    context.lineWidth = lineWidth;
    context.lineCap = "square";

    canvas.addEventListener("dblclick", fullscreen, false);

    context.font = tapeFontSize + "px " + tableFontFamily;
    timeSpace = context.measureText("00/00/00").width + padding * 2 + lineWidth;

    context.font = tableFontSize + "px " + tableFontFamily;

    //Global
    positionX = canvasOffsetX + lineWidth / 2;
    positionY = canvasOffsetY + lineWidth / 2;
    lineHeight = lineWidth * 2 + padding * 2 + tableFontSize;
    maxHeight = lineHeight * (data.tasks.length + 1);

    //Resize canvas
    canvas.width = canvasOffsetX + calculateCanvasWidth() + lineWidth;
    canvas.height = canvasOffsetY + maxHeight + lineWidth;

    context = canvas.getContext("2d");
    context.lineWidth = lineWidth;
    context.lineCap = "square";

    //Draw background
    context.rect(0, 0, canvas.width, canvas.height);
    context.fillStyle = "#ffffff";
    context.fill();
    context.fillStyle = "#000000";

    //Draw table -----------------------------------------------------------
    context.font = tableFontSize + "px " + tableFontFamily;
    context.textBaseline = "middle";
    context.beginPath();

    //Draw columns
    for (var i = 0; i < columnPair.length; i++) {
        drawColumn(columnPair[i][0], columnPair[i][1]);
    };

    //Draw TimeTape --------------------------------------------------------
    context.textAlign = "center";
    context.textBaseline = "middle";
    context.font = tapeFontSize + "px " + tableFontFamily;

    //Calculate project lifespan in milliseconds
    timeDifference = Date.parse(data.project.projectDeadline) - Date.parse(data.project.projectStartDate);

    //Calculate number of steps in tape
    numberOfSteps = Math.ceil(timeDifference / dayToMill(timeStep));

    //Draw time steps
    for (var i = 0; i < numberOfSteps; i++) {
        if (i > 0 && i < numberOfSteps) {
            context.moveTo(positionX + i * timeSpace, positionY);
            context.lineTo(positionX + i * timeSpace, positionY + lineHeight);
        }

        //Write date
        dateAtPoint = new Date(Date.parse(data.project.projectStartDate) + dayToMill(timeStep) * i);
        dateString = dateAtPoint.getDate() + "/" + (dateAtPoint.getMonth() + 1) + "/" + new String(dateAtPoint.getFullYear()).slice(2);
        context.fillText(dateString, positionX + i * timeSpace + timeSpace / 2, positionY + lineHeight / 2);
    };

    //Draw containing box Top -> Right -> Bottom -> Left
    context.moveTo(positionX, positionY);
    context.lineTo(positionX + numberOfSteps * timeSpace, positionY);
    context.lineTo(positionX + numberOfSteps * timeSpace, positionY + maxHeight);
    context.lineTo(positionX, positionY + maxHeight);
    context.lineTo(positionX, positionY);

    //Draw inner underline box
    context.moveTo(positionX, positionY + lineHeight);
    context.lineTo(positionX + numberOfSteps * timeSpace, positionY + lineHeight);

    //Finalise this section
    context.stroke();

    //Draw Bars ------------------------------------------------------------
    var startDateX,
        endDateX,
        currentDateX,
        barX,
        barY;

    for (var i = 0; i < data.tasks.length; i++) {
        startDateX = dateToPosition(data.tasks[i].taskStartDate);
        endDateX = dateToPosition(data.tasks[i].taskEndDate);

        context.beginPath();

        //Check if parent task
        taskParentCheck = false;
        if (taskParent.length > 0) {
            for (var j = 0; j < taskParent.length; j++) {
                if (data.tasks[i].taskID === taskParent[j]) {
                    taskParentCheck = true;
                    break;
                }
            };
        }

        //Setup Values
        barX = positionX + startDateX - (lineWidth % 2 === 0 ? 0 : 0.5);
        barY = positionY + lineHeight * (i + 1) + lineWidth + padding - (lineWidth % 2 === 0 ? 0 : 0.5);
        barW = endDateX - startDateX;
        barH = tableFontSize + 1;

        //Draw for each different 'bar'
        if (taskParentCheck) {
            context.moveTo(barX, barY);
            context.lineTo(barX, barY + barH);
            context.lineTo(barX + barH / 2, barY + barH / 2);
            context.lineTo(barX + barW - barH / 2, barY + barH / 2);
            context.lineTo(barX + barW, barY + barH);
            context.lineTo(barX + barW, barY);
        } else {
            if(barW === 0) {
                //Milestone
                context.moveTo(barX - barH / 2, barY + barH / 2);
                context.lineTo(barX, barY + barH);
                context.lineTo(barX + barH / 2, barY + barH / 2);
                context.lineTo(barX, barY);
            } else {
                //Normal
                context.rect(barX, barY, barW, barH);
            }
        }
        context.fill();
        context.beginPath();

        //Draw predecessor Lines
        var lineStartX,
            lineStartY,
            lineEndX,
            lineEndY,
            successorTask;

        for (var j = 0; j < predecPair.length; j++) {
            if (predecPair[j][0] == data.tasks[i].taskID) {
                successorTask = getTaskById(predecPair[j][1]);
                lineStartX = positionX + endDateX;
                lineStartY = positionY + barH / 2 + lineHeight * (i + 1) + lineWidth + padding;
                lineEndX = positionX + dateToPosition(successorTask.taskStartDate);
                lineEndY = positionY + barH / 2 + lineHeight * (getTaskIndexById(successorTask.taskID) + 1) + lineWidth + padding;

                context.moveTo(lineStartX - 10, lineStartY);
                context.lineTo(lineStartX - 10, lineEndY);
                context.lineTo(lineEndX, lineEndY);
                context.stroke();
            }
        };
    };

    //Draw current date line
    context.beginPath();
    currentDateX = dateToPosition(new Date());
    context.moveTo(positionX + currentDateX, positionY + lineHeight + lineWidth / 2);
    context.lineTo(positionX + currentDateX, positionY + maxHeight - lineWidth / 2);
    context.closePath();
    context.strokeStyle = "#9999ff";
    context.stroke();

    function drawColumn(fieldName, columnName) {
        var cellWidth,
            maxWidth,
            columnY;

        //Calculate maxWidth
        maxWidth = context.measureText(columnName).width;
        for (var i = 0; i < data.tasks.length; i++) {
            cellWidth = context.measureText(data.tasks[i][fieldName]).width;
            maxWidth = cellWidth > maxWidth ? cellWidth : maxWidth;
        };
        maxWidth += padding * 2 + lineWidth * 2;

        //Draw columnName
        context.textAlign = "center";
        context.fillText(columnName, positionX + maxWidth / 2, positionY + lineHeight / 2);

        //Draw cells
        context.textAlign = "left";
        for (var i = 0; i < data.tasks.length; i++) {
            columnY = positionY + lineHeight * (i + 1);

            context.moveTo(positionX, columnY);
            context.lineTo(positionX + maxWidth, columnY);

            context.fillText(data.tasks[i][fieldName], positionX + lineWidth + padding, columnY + lineHeight / 2);
        };

        //Draw Borders -- Top -> Left -> Bottom
        context.moveTo(positionX + maxWidth, positionY);
        context.lineTo(positionX, positionY);
        context.lineTo(positionX, positionY + maxHeight);
        context.lineTo(positionX + maxWidth, positionY + maxHeight);

        positionX += maxWidth;
    }

    function calculateCanvasWidth() {
        var canvasWidth,
            tableWidth,
            columnWidth,
            cellWidth,
            timeDifference,
            numberOfSteps,
            timeWidth;

        canvasWidth = 0;

        //Calculate table width
        for (var i = 0; i < columnPair.length; i++) {
            columnWidth = context.measureText(columnPair[i][1]).width;
            for (var j = 0; j < data.tasks.length; j++) {
                cellWidth = context.measureText(data.tasks[j][columnPair[i][0]]).width;
                columnWidth = cellWidth > columnWidth ? cellWidth : columnWidth;
            };
            columnWidth += padding * 2 + lineWidth * 2;
            canvasWidth += columnWidth;
        };

        //Calculate timetape width
        timeDifference = Date.parse(data.project.projectDeadline) - Date.parse(data.project.projectStartDate);
        numberOfSteps = Math.ceil(timeDifference / dayToMill(timeStep));
        timeWidth = numberOfSteps * timeSpace;
        canvasWidth += timeWidth;

        return canvasWidth;
    }

    //date : String
    function dateToPosition(date) {
        var DateIn,
            DateStep,
            DateX;

        DateIn = Date.parse(date) - Date.parse(data.project.projectStartDate);
        DateStep = DateIn / dayToMill(timeStep);
        DateX = DateStep * timeSpace;

        return Math.round(DateX);
    }

    //id : object
    function getTaskById(id) {
        var task;
        for (var i = 0; i < data.tasks.length; i++) {
            task = data.tasks[i];
            if (task.taskID == id) {
                return task;
            }
        };
    }

    //id : integer
    function getTaskIndexById(id) {
        var task;
        for (var i = 0; i < data.tasks.length; i++) {
            task = data.tasks[i];
            if (task.taskID == id) {
                return i;
            }
        };
    }

    function fullscreen(){
        if (canvas.webkitRequestFullScreen) {
            canvas.webkitRequestFullScreen();
        } else {
            canvas.mozRequestFullScreen();
        }
    }
}

window.addEventListener("load", read, false);

//days : integer
function dayToMill(days) {
    return days * 24 * 60 * 60 * 1000;
}

function saveCanvas(filename, filetype, canvas) {
    var data,
        dataInput,
        filenameInput,
        filetypeInput,
        dataInput,
        formInput;

    data = canvas.toDataURL("image/png");
    data = data.substr(data.indexOf(',') + 1).toString();

    dataInput = document.createElement("input");
    dataInput.setAttribute("name", "imagedata");
    dataInput.setAttribute("value", data);

    filenameInput = document.createElement("input");
    filenameInput.setAttribute("name", "filename");
    filenameInput.setAttribute("value", filename);

    filetypeInput = document.createElement("input");
    filetypeInput.setAttribute("name", "filetype");
    filetypeInput.setAttribute("value", filetype);

    formInput = document.createElement("form");
    formInput.method = "POST";
    formInput.action = "image.php";
    formInput.appendChild(dataInput);
    formInput.appendChild(filenameInput);
    formInput.appendChild(filetypeInput);

    document.body.appendChild(formInput);
    formInput.submit();
    document.body.removeChild(formInput);
}
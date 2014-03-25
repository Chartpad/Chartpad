var chartpad = chartpad || {};

chartpad.gantt = (function () {
    "use strict";

    var load,
        parseData,
        initialise,
        drawColumn,
        calculateGanttWidth;
        
    var _data;

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

    load = function () {
        $.ajax({
            type: "GET",
            url: "return.php",
            data: {"pid": $("body > span").first().attr("id")},
            success: parseData,
            datatype: "text"
        });
    };

    parseData = function (data) {
        console.log(data);
        _data = data;
    };

    initialise = function () {
        //settings
        tableFontFamily = _data.project.ganttTableFontFamily || "Calibri";
        tableFontSize = _data.project.ganttTableFontSize || 14;
        tapeFontSize = _data.project.ganttTapeFontSize || 10;
        lineWidth = _data.project.ganttLineWidth || 1;
        padding = _data.ganttPadding || 5;
        canvasOffsetX = _data.project.canvasOffsetX || 0;
        canvasOffsetY = _data.project.canvasOffsetY || 0;
        timeStep = _data.project.ganttTimeStep || 7;

        //fields
        timeSpace = 0;

        //Columns
        // [Fieldname, Displayed Name]
        columnPair = [["taskName", "Task Name"],
            ["taskDuration", "Duration"],
            ["taskStartDate", "Start Date"],
            ["taskEndDate", "End Date"],
            ["taskPredecessor", "Predecessor"]];

        //Gather data
        taskParent = []; //Parents
        predecPair = []; //Predecessor -- Successor Pairs
        for (var i = 0; i < _data.tasks.length; i++) {
            task = _data.tasks[i];
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
        maxHeight = lineHeight * (_data.tasks.length + 1);

        //Resize canvas
        canvas.width = canvasOffsetX + calculateGanttWidth() + lineWidth;
        canvas.height = canvasOffsetY + maxHeight + lineWidth;

        context = canvas.getContext("2d");
        context.lineWidth = lineWidth;
        context.lineCap = "square";
    };

    drawTable = function () {
        context = canvas.getContext("2d");
        context.lineWidth = lineWidth;
        context.lineCap = "square";

        //Draw background
        context.rect(0, 0, canvas.width, canvas.height);
        context.fillStyle = "#000000";
        context.fill();
        context.fillStyle = "#ffffff";

        //Draw table -----------------------------------------------------------
        context.font = tableFontSize + "px " + tableFontFamily;
        context.textBaseline = "middle";
        context.beginPath();

        //Draw columns
        for (var i = 0; i < columnPair.length; i++) {
            drawColumn(columnPair[i][0], columnPair[i][1]);
        };
    };

    drawTimeTape = function () {
        context.textAlign = "center";
        context.textBaseline = "middle";
        context.font = tapeFontSize + "px " + tableFontFamily;

        //Calculate project lifespan in milliseconds
        timeDifference = Date.parse(_data.project.projectDeadline) - Date.parse(_data.project.projectStartDate);

        //Calculate number of steps in tape
        numberOfSteps = Math.ceil(timeDifference / dayToMill(timeStep));

        //Draw time steps
        for (var i = 0; i < numberOfSteps; i++) {
            if (i > 0 && i < numberOfSteps) {
                context.moveTo(positionX + i * timeSpace, positionY);
                context.lineTo(positionX + i * timeSpace, positionY + lineHeight);
            }

            //Write date
            dateAtPoint = new Date(Date.parse(_data.project.projectStartDate) + dayToMill(timeStep) * i);
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
    };

    drawBars = function () {

    };

    drawColumn = function (fieldName, columnName) {
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
            for (var j = 0; j < _data.tasks.length; j++) {
                cellWidth = context.measureText(_data.tasks[j][columnPair[i][0]]).width;
                columnWidth = cellWidth > columnWidth ? cellWidth : columnWidth;
            };
            columnWidth += padding * 2 + lineWidth * 2;
            canvasWidth += columnWidth;
        };

        //Calculate timetape width
        timeDifference = Date.parse(_data.project.projectDeadline) - Date.parse(_data.project.projectStartDate);
        numberOfSteps = Math.ceil(timeDifference / dayToMill(timeStep));
        timeWidth = numberOfSteps * timeSpace;
        canvasWidth += timeWidth;

        return canvasWidth;
    };

    calculateGanttWidth = function () {
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
            for (var j = 0; j < _data.tasks.length; j++) {
                cellWidth = context.measureText(_data.tasks[j][columnPair[i][0]]).width;
                columnWidth = cellWidth > columnWidth ? cellWidth : columnWidth;
            };
            columnWidth += padding * 2 + lineWidth * 2;
            canvasWidth += columnWidth;
        };

        //Calculate timetape width
        timeDifference = Date.parse(_data.project.projectDeadline) - Date.parse(data.project.projectStartDate);
        numberOfSteps = Math.ceil(timeDifference / dayToMill(timeStep));
        timeWidth = numberOfSteps * timeSpace;
        canvasWidth += timeWidth;

        return canvasWidth;
    };

    return {
        "load" : load
    }
}());

$(document).read(chartpad.gantt.load);
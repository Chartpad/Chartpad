var chartpad = chartpad || {};

chartpad.pert = (function () {
	"use strict";

	var load,
		parseData,
		initialise,
		draw,
		myMoveStart,
		myMoveEnd,
		myMove,
		saveCanvas,
		saveData,
		saveSuccessful,
		saveFailed,
		setupInterface,
		parseHex,
		allSuccessors;

	var _data,
		_mouse = {
			x : 0,
			y : 0
		};

	var canvas,
		context,
		canvasWidth,
		canvasHeight,
		lineWidth,
		padding,
		fontSize,
		fontFamily,
		mouseOffsetX,
		mouseOffsetY,
		dragging = [],
		boxes,
		backgroundColor,
		boxBorderColor,
		boxBackgroundColor,
		boxLineColor,
		boxFontColor,
		canTouch,
		dateWidth,
		durationWidth;

	load = function() {
		$.ajax({
			type: "GET",
			url: "return.php",
			data: {"pid": $("body > span").first().attr("id")},
			success: parseData,
			datatype: "text"
		});
	};

	parseData = function (data) {
		_data = data;

		//Settings
		canvasWidth = (data.project.perWidth > 100) ? data.project.pertWidth || 640 : 3000;
		canvasHeight = (data.project.perheight > 100) ? data.project.pertHeight || 480 : 500;

		lineWidth = parseInt(data.project.pertLineWidth, 10) || 2;
		padding = parseInt(data.project.pertPadding, 10) || 5;
		fontFamily = data.project.pertFontFamily || "Calibri";
		fontSize = parseInt(data.project.pertFontSize, 10) || 14;

		backgroundColor = "#" + (data.project.pertBackgroundColor || "ffffff");
		boxBorderColor = "#" + (data.project.pertBorderColor || "000000");
		boxBackgroundColor = "#" + (data.project.pertBoxBackgroundColor || "ffffff");
		boxLineColor = "#" + (data.project.pertBoxLineColor || "000000");
		boxFontColor = "#" + (data.project.pertBoxFontColor || "000000");

		initialise();

		//Add handlers
		if (canTouch) {
			canvas.addEventListener("touchstart", myMoveStart, false);
			canvas.addEventListener("touchend", myMoveEnd, false);
		} else {
			canvas.addEventListener("mousedown", myMoveStart, false);
			canvas.addEventListener("mouseup", myMoveEnd, false);
		}

		setupInterface();
		draw();
	};

	initialise = function () {
		var task, tempBoxes, minWidth, textWidth;

		//Initialise
		canvas = document.getElementById("pert");
		canvas.width = canvasWidth;
		canvas.height = canvasHeight;
		context = canvas.getContext("2d");

		context.lineWidth = lineWidth;
		context.font = fontSize + "px " + fontFamily;
		context.textAlign = "center";
		context.textBaseline = "middle";

		canTouch = !!('ontouchstart' in window) || !!('onmsgesturechange' in window);

		//Calculate using setup context
		dateWidth = context.measureText("0000-00-00").width;
		durationWidth = context.measureText("0000 Days").width;
		minWidth = lineWidth * 4 + padding * 6 + dateWidth * 2 + durationWidth;

/*
******************************************************************************

	Add boxes at front and end for Project Start and Project End

******************************************************************************
*/

		tempBoxes = [];
		//Add Project Start
		tempBoxes[0] = {
			id : 0,
			x : 0,
			y : 0,
			w : minWidth,
			h : lineWidth * 4 + padding * 6 + fontSize * 3,
			data : {
				name : "Start",
				earliestStart : _data.project.projectStartDate,
				latestStart : _data.project.projectStartDate,
				earliestEnd : _data.project.projectStartDate,
				latestEnd : _data.project.projectStartDate,
				duration : 0,
				slack : 0
			},
			successors : []
		};


		//Add Project End
		tempBoxes[1] = {
			id : 0,
			x : 0,
			y : 0,
			w : minWidth,
			h : lineWidth * 4 + padding * 6 + fontSize * 3,
			data : {
				name : "End",
				earliestStart : _data.project.projectDeadline,
				latestStart : _data.project.projectDeadline,
				earliestEnd : _data.project.projectDeadline,
				latestEnd : _data.project.projectDeadline,
				duration : 0,
				slack : 0
			},
			successors : []
		};

		//Add Key

		//Add Tasks
		for (var i = 0; i < _data.tasks.length; i++) {
			task = _data.tasks[i];
			textWidth = lineWidth * 2 + padding * 2 + context.measureText(task.taskName).width;

			tempBoxes[parseInt(task.taskID, 10) + 1] = {
				id : parseInt(task.taskID, 10),
				x : task.pertX || 0,
				y : task.pertY || 0,
				w : minWidth > textWidth ? minWidth : textWidth,
				h : lineWidth * 4 + padding * 6 + fontSize * 3,
				data : {
					name : task.taskName,
					earliestStart : task.taskEarliestStartDate,
					latestStart : task.taskStartDate,
					earliestEnd : task.taskEndDate,
					latestEnd : task.taskLatestEndDate,
					duration : ((Date.parse(task.taskEndDate) - Date.parse(task.taskEarliestStartDate)) + (Date.parse(task.taskStartDate) - Date.parse(task.taskLatestEndDate)) / 2) / 1000 / 60 / 60 / 24 + " Days",
					slack : (Date.parse(task.taskStartDate) - Date.parse(task.taskEarliestStartDate)) + (Date.parse(task.taskLatestEndDate) - Date.parse(task.taskEndDate)) / 2
				},
				predecessor : null,
				successors : []
			};
		};

		console.log(_data);
		console.log(tempBoxes);

		//Add successors / predecessors
		for (var i = 0; i < _data.tasks.length; i++) {
			task = _data.tasks[i];
			if (task.taskPredecessor !== null && task.taskPredecessor !== "0") {
				//Add box to box
				tempBoxes[parseInt(task.taskID, 10) + 1].predecessor = tempBoxes[parseInt(task.taskPredecessor, 10) + 1];
				tempBoxes[parseInt(task.taskPredecessor, 10) + 1].successors.push(tempBoxes[parseInt(task.taskID, 10) + 1]);
			} else {
				//Add box to Project Start
				tempBoxes[parseInt(task.taskID, 10) + 1].predecessor = tempBoxes[0];
				tempBoxes[0].successors.push(tempBoxes[parseInt(task.taskID, 10) + 1]);
			}
		};

		//Attach all final tasks to Project End
		tempBoxes.map(function (task) {
			if (task.successors.length === 0 && task.id !== 0 && task.id !== 1) {
				task.successors.push(tempBoxes[1]);
			}
		});

		//Repack into smaller array
		boxes = [];
		tempBoxes.map(function (task) {
			boxes.push(task);
		});

		console.log(boxes);
	};

	setupInterface = function () {
		$(".header img").bind("click", function (ev) {
			window.close();
		});

		//Settings Box
		$("#settingsToggle").bind("click", function (ev) {
			toggleSettings();
		});

		function toggleSettings() {
			if (parseInt($(".settingsBox").css("right"), 10) < 0) {
				//Position
				$(".settingsBox").css("right", "10px");
				$(".popup").css("top", 30 + parseInt($(".settingsBox").css("top"), 10) + parseInt($(".settingsBox").css("height"), 10));

				//Default values
				$("#settingsDimensionWidth").val(canvas.width);
				$("#settingsDimensionHeight").val(canvas.height);

			} else {
				$(".settingsBox").css("right", "-999px");
				$(".popup").css("top", "61px");
			}
		};
	};

	saveData = function () {
	};

	saveSuccessful = function (data) {
	};

	saveFailed = function (xhr, status, error) {
	};

	draw = function (ev) {
		var box, successor;

		//Draw background // Clear
		context.beginPath();
		context.fillStyle = backgroundColor;
		context.rect(0, 0, canvasWidth, canvasHeight);
		context.fill();

		//Draw lines
		context.strokeStyle = boxLineColor;
		context.fillStyle = boxLineColor;
		context.lineJoin = "round";
		for (var i = 0; i < boxes.length; i++) {
			box = boxes[i];

			//Draw Lines to children
			if (box.successors.length > 0) {
				for (var j = 0; j < box.successors.length; j++) {
					successor = box.successors[j];
					//Draw Line
					context.beginPath();
					context.moveTo(box.x + box.w, box.y + box.h / 2);
					context.lineTo(box.x + box.w + lineWidth * 15, box.y + box.h / 2);
					context.lineTo(successor.x - lineWidth * 15, successor.y + successor.h / 2)
					context.lineTo(successor.x, successor.y + successor.h / 2);
					context.stroke();

					//Draw Arrow
					context.beginPath();
					context.moveTo(successor.x, successor.y + successor.h / 2);
					context.lineTo(successor.x - lineWidth * 6, successor.y + successor.h / 2 - lineWidth * 3);
					context.quadraticCurveTo(successor.x, successor.y + successor.h / 2, successor.x - lineWidth * 6, successor.y + successor.h / 2 + lineWidth * 3)
					context.closePath();
					context.fill();
				};
			}
		}
		context.lineJoin = "miter";

		//Draw box background
		context.beginPath();
		context.fillStyle = boxBackgroundColor;
		for (var i = 0; i < boxes.length; i++) {
			box = boxes[i];
			context.rect(box.x, box.y, box.w, box.h);
		}
		context.fill();

		//Draw Content
		context.fillStyle = boxFontColor;
		context.beginPath();
		for (var i = 0; i < boxes.length; i++) {
			box = boxes[i];

			//Draw Top Lines
			context.moveTo(box.x, box.y + lineWidth * 2 + padding * 2 + fontSize);
			context.lineTo(box.x + box.w, box.y + lineWidth * 2 + padding * 2 + fontSize);

			context.moveTo(box.x + lineWidth * 2 + padding * 2 + dateWidth, box.y);
			context.lineTo(box.x + lineWidth * 2 + padding * 2 + dateWidth, box.y + lineWidth * 2 + padding * 2 + fontSize);

			context.moveTo(box.x + box.w - lineWidth * 2 - padding * 2 - dateWidth, box.y);
			context.lineTo(box.x + box.w - lineWidth * 2 - padding * 2 - dateWidth, box.y + lineWidth * 2 + padding * 2 + fontSize);

			//Draw Bottom Lines
			context.moveTo(box.x, box.y + box.h - lineWidth * 2 - padding * 2 - fontSize);
			context.lineTo(box.x + box.w, box.y + box.h - lineWidth * 2 - padding * 2 - fontSize);

			context.moveTo(box.x + lineWidth * 2 + padding * 2 + dateWidth, box.y + box.h);
			context.lineTo(box.x + lineWidth * 2 + padding * 2 + dateWidth, box.y + box.h - lineWidth * 2 - padding * 2 - fontSize);

			context.moveTo(box.x + box.w - lineWidth * 2 - padding * 2 - dateWidth, box.y + box.h);
			context.lineTo(box.x + box.w - lineWidth * 2 - padding * 2 - dateWidth, box.y + box.h - lineWidth * 2 - padding * 2 - fontSize);

			//Draw Text
			context.fillText(box.data.earliestStart, box.x + padding + lineWidth + dateWidth / 2, box.y + padding + lineWidth + fontSize / 2);
			context.fillText(box.data.duration, box.x + box.w / 2, box.y + lineWidth + padding + fontSize / 2);
			context.fillText(box.data.earliestEnd, box.x + box.w - padding - lineWidth - dateWidth / 2, box.y + padding + lineWidth + fontSize / 2);
			context.fillText(box.data.name, box.x + box.w / 2, box.y + box.h / 2);
			context.fillText(box.data.latestStart, box.x + padding + lineWidth + dateWidth / 2, box.y + box.h - padding - lineWidth - fontSize / 2);
			context.fillText(box.data.slack, box.x + box.w / 2, box.y + box.h - lineWidth - padding - fontSize / 2);
			context.fillText(box.data.latestEnd, box.x + box.w - padding - lineWidth - dateWidth / 2, box.y + box.h - padding - lineWidth - fontSize / 2);
		};
		context.stroke();

		//Draw Border
		context.beginPath();
		for (var i = 0; i < boxes.length; i++) {
			box = boxes[i];
			context.rect(box.x, box.y, box.w, box.h);
		};
		context.strokeStyle = boxBorderColor;
		context.stroke();
	};

	myMoveStart = function (ev) {
		var pointX, pointY;
		pointX = ev.pageX || ev.changedTouches[0].pageX;
		pointY = ev.pageY || ev.changedTouches[0].pageY;

		//find clicked element
		for (var i = 0; i < boxes.length; i++) {
			if (pointX < boxes[i].x + boxes[i].w + canvas.offsetLeft &&
			pointX > boxes[i].x + canvas.offsetLeft &&
			pointY < boxes[i].y + boxes[i].h + canvas.offsetTop &&
			pointY > boxes[i].y + canvas.offsetTop) {

				//Set previous mouse x/y
				_mouse.x = pointX;
				_mouse.y = pointY;

				//Add clicked element to list of draggees
				dragging.push(boxes[i]);

				//Add all dragees children if (Shift Clicking)
				if (ev.shiftKey) {
					dragging = dragging.concat(allSuccessors(boxes[i]));
					dragging.push(boxes[1]);
				}

				if (canTouch) {
					canvas.addEventListener("touchmove", myMove, false);
				} else {
					canvas.addEventListener("mousemove", myMove, false);
				}
				break;
			}
		};
	};

	myMoveEnd = function (ev) {
		if(canTouch) {
			canvas.removeEventListener("touchmove", myMove, false);
		} else {
			canvas.removeEventListener("mousemove", myMove, false);
		}
		dragging = [];
	};

	myMove = function (ev) {
		ev.preventDefault();
		var pointX, pointY, xChange, yChange;
		pointX = ev.pageX || ev.changedTouches[0].pageX;
		pointY = ev.pageY || ev.changedTouches[0].pageY;

		xChange = pointX - _mouse.x;
		yChange = pointY - _mouse.y;

		for (var i = 0; i < dragging.length; i++) {
			dragging[i].x += xChange;
			dragging[i].y += yChange;
		};

		_mouse.x = pointX;
		_mouse.y = pointY;

		draw();
	};

	saveCanvas = function (filename, filetype, canvas) {
	}

	parseHex = function (value) {
	}

	allSuccessors = function (box) {
		var aBoxes = [];
		for (var i = 0; i < box.successors.length; i++) {
			if (box.successors[i].id !== 0) {
				aBoxes.push(box.successors[i]);
			}
			aBoxes = aBoxes.concat(allSuccessors(box.successors[i]));
		};
		return aBoxes;
	}

	return {
		"load": load,
		"draw": draw,
	}
}());

$(document).ready(chartpad.pert.load);
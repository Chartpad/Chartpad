var chartpad = chartpad || {};

chartpad.wbt = (function () {
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
		allChildren;

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
		box,
		backgroundColor,
		boxBorderColor,
		boxBackgroundColor,
		boxLineColor,
		boxFontColor,
		canTouch;

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
		//console.log(data); //returned data

		_data = data;

		//Settings
		canvasWidth = data.project.wbtWidth || 640;
		canvasHeight = data.project.wbtHeight || 480;

		lineWidth = parseInt(data.project.wbtLineWidth, 10) || 2;
		padding = parseInt(data.project.wbtPadding, 10) || 5;
		fontFamily = data.project.wbtFontFamily || "Calibri";
		fontSize = parseInt(data.project.wbtFontSize, 10) || 14;

		backgroundColor = "#" + (data.project.wbtBackgroundColor || "ffffff");
		boxBorderColor = "#" + (data.project.wbtBorderColor || "000000");
		boxBackgroundColor = "#" + (data.project.wbtBoxBackgroundColor || "ffffff");
		boxLineColor = "#" + (data.project.wbtBoxLineColor || "000000");
		boxFontColor = "#" + (data.project.wbtBoxFontColor || "000000");

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
		var task, tempBoxes;

		//Initialise
		canvas = document.getElementById("wbt");
		canvas.width = canvasWidth;
		canvas.height = canvasHeight;
		context = canvas.getContext("2d");

		context.lineWidth = lineWidth;
		context.font = fontSize + "px " + fontFamily;
		context.textAlign = "center";
		context.textBaseline = "middle";

		canTouch = !!('ontouchstart' in window) || !!('onmsgesturechange' in window);

		//Make base boxes
		tempBoxes = [];
		for (var i = 0; i < _data.tasks.length; i++) {
			task = _data.tasks[i];
			tempBoxes[task.taskID] = {
				id : task.taskID,
				text : task.taskName,
				x : parseInt(task.wbtX, 10) < canvas.width ? parseInt(task.wbtX, 10) : 10,
				y : parseInt(task.wbtY, 10) < canvas.height ? parseInt(task.wbtY, 10): 10,
				w : context.measureText(task.taskName).width + padding * 2 + lineWidth * 2,
				h : fontSize + padding * 2 + lineWidth * 2,
				parent : null,
				children : []
			};
		};

		//Update Parent / Children
		for (var i = 0; i < _data.tasks.length; i++) {
			task = _data.tasks[i];
			if (task.parentID !== null && task.parentID !== "0") {
				tempBoxes[task.taskID].parent = tempBoxes[task.parentID];
				tempBoxes[task.parentID].children.push(tempBoxes[task.taskID]);
			}
		}

		//Repack into boxes for iteration
		boxes = [];
		for (var i = 0; i < tempBoxes.length; i++) {
			if (tempBoxes[i] !== undefined && tempBoxes[i] !== null) {
				boxes.push(tempBoxes[i]);
			}
		};
		//console.log(boxes); //parsed boxes
	};

	setupInterface = function () {
		$(".header img").bind("click", function (ev) {
			window.close();
		});

		$("#wbt").bind("contextmenu", function (ev) {
			ev.preventDefault();
			toggleSettings();
		});

		//** Change Behaviour Depending on Browser **//
		//backgroundcolor sliders
		if(!Modernizr.inputtypes.range) {
			document.getElementById("settingsBackgroundForm").innerHTML = "";

			var colorInput = document.createElement("input");
			colorInput.id = "settingsBackgroundColor";
			colorInput.type = "text";
			document.getElementById("settingsBackgroundForm").appendChild(colorInput);

			//add functionality
			$("#settingsBackgroundColor").bind("keyup", function (ev) {
				var reg = new RegExp("[0-9a-fA-F]{6}");
				var color = $("#settingsBackgroundForm input").val();
				backgroundColor = "#" + reg.exec(color) || "#ffffff";
				draw();
			});
		} else {
			document.getElementById("settingsBackgroundRed").value = parseInt(backgroundColor.substr(1, 2), 16);
			document.getElementById("settingsBackgroundGreen").value = parseInt(backgroundColor.substr(3, 2), 16);
			document.getElementById("settingsBackgroundBlue").value = parseInt(backgroundColor.substr(5, 2), 16);
			backgroundColor = colourSliderBackground("settingsBackground");
			$("#settingsBackgroundForm input").bind("change", function (ev) {
				backgroundColor = colourSliderBackground("settingsBackground");
				draw();
			});
		}


		//boxbordercolor sliders
		if(!Modernizr.inputtypes.range) {
		} else {
			document.getElementById("settingsBoxBorderRed").value = parseInt(boxBorderColor.substr(1, 2), 16);
			document.getElementById("settingsBoxBorderGreen").value = parseInt(boxBorderColor.substr(3, 2), 16);
			document.getElementById("settingsBoxBorderBlue").value = parseInt(boxBorderColor.substr(5, 2), 16);
			boxBorderColor = colourSliderBackground("settingsBoxBorder");
			$("#settingsBoxBorderForm input").bind("change", function (ev) {
				boxBorderColor = colourSliderBackground("settingsBoxBorder");
				draw();
			});
		}


		//boxbackgroundcolor sliders
		if(!Modernizr.inputtypes.range) {
		} else {
			document.getElementById("settingsBoxBackgroundRed").value = parseInt(boxBackgroundColor.substr(1, 2), 16);
			document.getElementById("settingsBoxBackgroundGreen").value = parseInt(boxBackgroundColor.substr(3, 2), 16);
			document.getElementById("settingsBoxBackgroundBlue").value = parseInt(boxBackgroundColor.substr(5, 2), 16);
			boxBackgroundColor = colourSliderBackground("settingsBoxBackground");
			$("#settingsBoxBackgroundForm input").bind("change", function (ev) {
				boxBackgroundColor = colourSliderBackground("settingsBoxBackground");
				draw();
			});
		}


		//fontcolor sliders
		if(!Modernizr.inputtypes.range) {
		} else {
			document.getElementById("settingsFontColourRed").value = parseInt(boxFontColor.substr(1, 2), 16);
			document.getElementById("settingsFontColourGreen").value = parseInt(boxFontColor.substr(3, 2), 16);
			document.getElementById("settingsFontColourBlue").value = parseInt(boxFontColor.substr(5, 2), 16);
			boxFontColor = colourSliderBackground("settingsFontColour");
			$("#settingsFontColourForm input").bind("change", function (ev) {
				boxFontColor = colourSliderBackground("settingsFontColour");
				draw();
			});
		}

		//linkcolor sliders
		if(!Modernizr.inputtypes.range) {
		} else {
			document.getElementById("settingsBoxLinkRed").value = parseInt(boxLineColor.substr(1, 2), 16);
			document.getElementById("settingsBoxLinkGreen").value = parseInt(boxLineColor.substr(3, 2), 16);
			document.getElementById("settingsBoxLinkBlue").value = parseInt(boxLineColor.substr(5, 2), 16);
			boxLineColor = colourSliderBackground("settingsBoxLink");
			$("#settingsBoxLinkForm input").bind("change", function (ev) {
				boxLineColor = colourSliderBackground("settingsBoxLink");
				draw();
			});
		}

		//linewidth
		if(!Modernizr.inputtypes.number) {
		} else {
			document.getElementById("settingsLineWidth").value = lineWidth;
			$("#settingsLineWidth").bind("change", function (ev) {
				lineWidth = document.getElementById("settingsLineWidth").value;
				context.lineWidth = lineWidth;
				draw();
			});
		}

		//padding
		if(!Modernizr.inputtypes.number) {
		} else {
			document.getElementById("settingsPadding").value = padding;
			$("#settingsPadding").bind("change", function (ev) {
				padding = document.getElementById("settingsPadding").value;
				initialise();
				draw();
			});
		}

		//font
		if(!Modernizr.inputtypes.number) {
		} else {
			document.getElementById("settingsFontSize").value = fontSize;
			$("#settingsFontSize").bind("change", function (ev) {
				fontSize = parseInt(document.getElementById("settingsFontSize").value, 10);
				context.font = fontSize + "px " + fontFamily;
				draw();
			});
		}

		//Export
		$("#savePNG").bind("click", function (ev) {
			saveCanvas("WBT-"+_data.project.projectName, "png", canvas);
		});

		$("#saveJPEG").bind("click", function (ev) {
			saveCanvas("WBT-"+_data.project.projectName, "jpg", canvas);
		});

		$("#saveGIF").bind("click", function (ev) {
			saveCanvas("WBT-"+_data.project.projectName, "gif", canvas);
		});

		//Save
		$("#saveData").bind("click", function (ev) {
			saveData();
		});

		//Settings Box
		$("#settingsToggle").bind("click", function (ev) {
			toggleSettings();
		});

		//Settings
		$("#settingsDimensionForm input").bind("keyup", function (ev) {
			var fWidth,
				fHeight;

			fWidth = parseInt($("#settingsDimensionWidth").val(), 10);
			fHeight = parseInt($("#settingsDimensionHeight").val(), 10);

			canvasWidth = fWidth > 100 ? fWidth : 100;
			canvasHeight = fHeight > 100 ? fHeight : 100;

			canvas.width = canvasWidth;
			canvas.height = canvasHeight;
			context = canvas.getContext("2d");

			context.lineWidth = lineWidth;
			context.font = fontSize + "px " + fontFamily;
			context.textAlign = "center";
			context.textBaseline = "middle";

			draw();
		});

		function colourSliderBackground(prefix) {
			var fRed = parseHex(document.getElementById(prefix+"Red").value),
				fGreen = parseHex(document.getElementById(prefix+"Green").value),
				fBlue = parseHex(document.getElementById(prefix+"Blue").value);
			document.getElementById(prefix+"Red").style.background = "-webkit-linear-gradient(left, #00"+fGreen+fBlue+" 0% , #ff"+fGreen+fBlue+" 100%)";
			document.getElementById(prefix+"Green").style.background = "-webkit-linear-gradient(left, #"+fRed+"00"+fBlue+" 0% , #"+fRed+"ff"+fBlue+" 100%)";
			document.getElementById(prefix+"Blue").style.background = "-webkit-linear-gradient(left, #"+fRed+fGreen+"00 0% , #"+fRed+fGreen+"ff 100%)";
			return "#" + fRed + fGreen + fBlue;
		};

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
		//Add options to settings object, add boxes to sBoxes

		//pack boxes and send to update
		var sBoxes = [],
			sSettings;

		sSettings = {
			projectID : _data.project.projectID,
			canvasWidth : canvas.width,
			canvasHeight: canvas.height,
			lineWidth : lineWidth,
			padding : padding,
			fontSize : fontSize,
			fontFamily : fontFamily,
			backgroundColor : backgroundColor.substring(1),
			boxBorderColor : boxBorderColor.substring(1),
			boxBackgroundColor : boxBackgroundColor.substring(1),
			boxLineColor : boxLineColor.substring(1),
			boxFontColor : boxFontColor.substring(1)
		}

		console.log(sSettings);

		for (var i = 0; i < boxes.length; i++) {
			sBoxes.push({
				id : boxes[i].id,
				x : boxes[i].x,
				y : boxes[i].y
			});
		};

		$.ajax({
			type: "POST",
			url: "update.php",
			data: {
				"b" : sBoxes,
				"s" : sSettings
			},
			success: saveSuccessful,
			error: saveFailed,
			datatype: "text"
		});
	};

	saveSuccessful = function (data) {
		console.log(data);
		$(".popup").text("Save Successful").css("right", "10px");
		setTimeout(function () {
			$(".popup").css("right", "-999px");
		}, 4000);
	};

	saveFailed = function (xhr, status, error) {
		$(".popup").html("Error <b>'"+error+"'</b>. Retry in 5s").css("right", "10px");
		setTimeout(function () {
			$(".popup").css("right", "-999px");
			setTimeout(saveData, 1000);
		}, 5000);
	};

	draw = function (ev) {
		var lineLeft, lineRight, lineTop, lineBottom, isHorizontal;
		//Clear canvas
		context.clearRect(0, 0, canvasWidth, canvasHeight);

		//console.log("Drawing..."); //Debug -- Used to see drawing time

		//Draw background
		context.beginPath();
		context.fillStyle = backgroundColor;
		context.rect(0, 0, canvasWidth, canvasHeight);
		context.fill();

		//Draw lines
		context.strokeStyle = boxLineColor;
		context.beginPath();
		for (var i = 0; i < boxes.length; i++) {
			box = boxes[i];

			//Draw Line to parent
			if(false) {
				context.moveTo(box.x, box.y);
				context.lineTo(box.x, box.parent.y + (box.h / 2 + 20));
			}

			//Draw Lines to children
			if (box.children.length > 0) {
				lineLeft = box.x;
				lineRight = box.x;
				lineTop = box.y;
				lineBottom = box.y;

				for (var j = 0; j < box.children.length; j++) {
					lineLeft = box.children[j].x < lineLeft ? box.children[j].x : lineLeft;
					lineRight = box.children[j].x > lineRight ? box.children[j].x : lineRight;
					lineTop = box.children[j].y < lineTop ? box.children[j].y : lineTop;
					lineBottom = box.children[j].y > lineBottom ? box.children[j].y : lineBottom;
				};

				//Vertical or Horizontal
				isHorizontal = (lineLeft - lineRight) < (lineTop - lineBottom);
				console.log(isHorizontal ? "Horozontal" : "Vertical");

				if (isHorizontal) {
					//Draw baseline
					context.moveTo(box.x, box.y);
					context.lineTo(box.x, box.y + (box.h / 2 + 20));
					context.moveTo(lineLeft - lineWidth / 2, box.y + (box.h / 2 + 20));
					context.lineTo(lineRight + lineWidth / 2, box.y + (box.h / 2 + 20));

					//Draw from children to baseline
					for (var j = 0; j < box.children.length; j++) {
						context.moveTo(box.children[j].x, box.children[j].y);
						context.lineTo(box.children[j].x, box.y + (box.h / 2 + 20));
					};
				} else {
					//Draw baseline
					context.moveTo(box.x, box.y);
					context.lineTo(box.x, lineBottom + lineWidth / 2);

					//Draw from children to baseline
					for (var j = 0; j < box.children.length; j++) {
						context.moveTo(box.children[j].x, box.children[j].y);
						context.lineTo(box.x, box.children[j].y);
					}
				}
			}
		}
		context.stroke();

		//Draw box background
		context.fillStyle = boxBackgroundColor;
		for (var i = 0; i < boxes.length; i++) {
			box = boxes[i];
			context.rect(box.x - box.w / 2, box.y - box.h / 2, box.w, box.h);
		}
		context.fill();

		//Draw box content
		context.fillStyle = boxFontColor;
		context.beginPath();
		for (var i = 0; i < boxes.length; i++) {
			box = boxes[i];

			//Draw content
			context.fillText(box.text, box.x, box.y);
			context.rect(box.x - box.w / 2, box.y - box.h / 2, box.w, box.h);
		};
		context.strokeStyle = boxBorderColor;
		context.stroke();
		context.lineJoin = "miter";
	};

	myMoveStart = function (ev) {
		var pointX, pointY;
		pointX = ev.pageX || ev.changedTouches[0].pageX;
		pointY = ev.pageY || ev.changedTouches[0].pageY;

		//find clicked element
		for (var i = 0; i < boxes.length; i++) {
			if (pointX < boxes[i].x + boxes[i].w / 2 + canvas.offsetLeft &&
			pointX > boxes[i].x - boxes[i].w / 2 + canvas.offsetLeft &&
			pointY < boxes[i].y + boxes[i].h / 2 + canvas.offsetTop &&
			pointY > boxes[i].y - boxes[i].h / 2 + canvas.offsetTop) {

				//Set previous mouse x/y
				_mouse.x = pointX;
				_mouse.y = pointY;

				//Add clicked element to list of draggees
				dragging.push(boxes[i]);

				//Add all dragees children if (Shift Clicking)
				if (ev.shiftKey) {
					dragging = dragging.concat(allChildren(boxes[i]));
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

	parseHex = function (value) {
		var val;
		val = parseInt(value, 10).toString(16);
		if(val.length < 2)
			val = "0" + val;
		return val;
	}

	allChildren = function (box) {
		var aBoxes = [];
		for (var i = 0; i < box.children.length; i++) {
			aBoxes.push(box.children[i]);
			aBoxes = aBoxes.concat(allChildren(box.children[i]));
		};
		return aBoxes;
	}

	return {
		"load": load,
		"draw": draw,
		"data" : function () {
			return _data;
		},
		"boxes" : function () {
			return boxes;
		}
	}
}());

$(document).ready(chartpad.wbt.load);
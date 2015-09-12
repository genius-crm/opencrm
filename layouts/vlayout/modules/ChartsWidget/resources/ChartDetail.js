/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

ChartsWidget_Detail_Js("ChartsWidget_ChartDetail_Js",{

	/**
	 * Function used to display message when there is no data from the server
	 */
	displayNoDataMessage : function() {
		$('#chartcontent').html('<div>'+app.vtranslate('JS_NO_CHART_DATA_AVAILABLE')+'</div>').css(
								{'text-align':'center', 'position':'relative', 'top':'100px'});
	},

	/**
	 * Function returns if there is no data from the server
	 */
	isEmptyData : function() {
		var jsonData = jQuery('input[name=data]').val();
		var data = JSON.parse(jsonData);
		var values = data['values'];
		if(jsonData == '' || values == '') {
			return true;
		}
		return false;
	}
},{
	/**
	 * Function returns instance of the chart type
	 */
	getInstance : function() {
		var chartType = jQuery('input[name=charttype]').val();
		var chartClassName = chartType.toCamelCase();
		var chartClass = window["Report_"+chartClassName + "_Js"];

		var instance = false;
		if(typeof chartClass != 'undefined') {
			instance = new chartClass();
			instance.postInitializeCalls();
		}
		return instance;
	},

	registerEventForChartGeneration : function() {
		var thisInstance = this;
		try {
			thisInstance.getInstance();	// instantiate the object and calls init function
			jQuery('#chartcontent').trigger(Vtiger_Widget_Js.widgetPostLoadEvent);
		} catch(error) {
			console.log("error");
			console.log(error);
			ChartsWidget_ChartDetail_Js.displayNoDataMessage();
			return;
		}
	},

	registerEvents : function(){
		this._super();
		this.registerEventForChartGeneration();
		jQuery('#chartDetailForm').validationEngine(app.validationEngineOptions);
	}
});


Vtiger_Pie_Widget_Js('Report_Piechart_Js',{},{

	postInitializeCalls : function() {
		var clickThrough = jQuery('input[name=clickthrough]').val();
		if(clickThrough != '') {
			var thisInstance = this;
			this.getContainer().on("jqplotDataClick", function(evt, seriesIndex, pointIndex, neighbor) {
				var linkUrl = thisInstance.data['links'][pointIndex];
				var numberOfClick = jQuery('input[name=numberOfClick]').val();
				if((linkUrl)&&(numberOfClick==1)) window.open(linkUrl);
				if(numberOfClick==2) numberOfClick=0;
				numberOfClick = parseInt(numberOfClick) + 1;
				jQuery('input[name=numberOfClick]').val(numberOfClick);
			});
			this.getContainer().on("jqplotDataHighlight", function(evt, seriesIndex, pointIndex, neighbor) {
				$('.jqplot-event-canvas').css( 'cursor', 'pointer' );
			});
			this.getContainer().on("jqplotDataUnhighlight", function(evt, seriesIndex, pointIndex, neighbor) {
				$('.jqplot-event-canvas').css( 'cursor', 'auto' );
			});
		}
	},

	postLoadWidget : function() {
		if(!ChartsWidget_ChartDetail_Js.isEmptyData()) {
			this.loadChart();
		}else{
			this.positionNoDataMsg();
		}
	},

	positionNoDataMsg : function() {
		ChartsWidget_ChartDetail_Js.displayNoDataMessage();
	},

	getPlotContainer : function(useCache) {
		if(typeof useCache == 'undefined'){
			useCache = false;
		}
		if(this.plotContainer == false || !useCache) {
			var container = this.getContainer();
			this.plotContainer = jQuery('#chartcontent');
		}
		return this.plotContainer;
	},

	init : function() {
		this._super(jQuery('#reportContentsDiv'));
	},

	generateData : function() {
		if(ChartsWidget_ChartDetail_Js.isEmptyData()) {
			ChartsWidget_ChartDetail_Js.displayNoDataMessage();
			return false;
		}

		var jsonData = jQuery('input[name=data]').val();
		var data = this.data = JSON.parse(jsonData);
		var values = data['values'];

		var chartData = [];
		for(var i in values) {
			chartData[i] = [];
			chartData[i].push(data['labels'][i]);
			chartData[i].push(values[i]);
		}
		return {'chartData':chartData, 'labels':data['labels'], 'data_labels':data['data_labels'], 'title' : data['graph_label']};
	}

});

Vtiger_Barchat_Widget_Js('Report_Verticalbarchart_Js', {},{

	postInitializeCalls : function() {
		jQuery('table.jqplot-table-legend').css('width','95px');
		var thisInstance = this;

		this.getContainer().on('jqplotDataClick', function(ev, gridpos, datapos, neighbor, plot) {
			var linkUrl = thisInstance.data['links'][neighbor[0]-1];
			var numberOfClick = jQuery('input[name=numberOfClick]').val();
			if((linkUrl)&&(numberOfClick==1)) window.open(linkUrl);
			if(numberOfClick==2) numberOfClick=0;
			numberOfClick = parseInt(numberOfClick) + 1;
			jQuery('input[name=numberOfClick]').val(numberOfClick);
		});

		this.getContainer().on("jqplotDataHighlight", function(evt, seriesIndex, pointIndex, neighbor) {
			$('.jqplot-event-canvas').css( 'cursor', 'pointer' );
		});
		this.getContainer().on("jqplotDataUnhighlight", function(evt, seriesIndex, pointIndex, neighbor) {
			$('.jqplot-event-canvas').css( 'cursor', 'auto' );
		});
	},

	postLoadWidget : function() {
		if(!ChartsWidget_ChartDetail_Js.isEmptyData()) {
			this.loadChart();
		}else{
			this.positionNoDataMsg();
		}
		this.postInitializeCalls();
	},

	positionNoDataMsg : function() {
		ChartsWidget_ChartDetail_Js.displayNoDataMessage();
	},

	getPlotContainer : function(useCache) {
		if(typeof useCache == 'undefined'){
			useCache = false;
		}
		if(this.plotContainer == false || !useCache) {
			var container = this.getContainer();
			this.plotContainer = jQuery('#chartcontent');
		}
		return this.plotContainer;
	},

	init : function() {
		this._super(jQuery('#reportContentsDiv'));
	},

	generateChartData : function() {
		if(ChartsWidget_ChartDetail_Js.isEmptyData()) {
			ChartsWidget_ChartDetail_Js.displayNoDataMessage();
			return false;
		}

		var jsonData = jQuery('input[name=data]').val();
		var data = this.data = JSON.parse(jsonData);
		var values = data['values'];

		var chartData = [];
		var yMaxValue = 0;

		if(data['type'] == 'singleBar') {
			chartData[0] = [];
			for(var i in values) {
				var multiValue = values[i];
				for(var j in multiValue) {
					chartData[0].push(multiValue[j]);
					if(multiValue[j] > yMaxValue) yMaxValue = multiValue[j];
				}
			}
		} else {
			chartData[0] = [];
			chartData[1] = [];
			chartData[2] = [];
			for(var i in values) {
				var multiValue = values[i];
				var info = [];
				for(var j in multiValue) {
					chartData[j].push(multiValue[j]);
					if(multiValue[j] > yMaxValue) yMaxValue = multiValue[j];
				}
			}
		}
		yMaxValue = yMaxValue + (yMaxValue*0.15);

		return {'chartData':chartData,
				'yMaxValue':yMaxValue,
				'labels':data['labels'],
				'data_labels':data['data_labels'],
				'title' : data['graph_label']
			};
	}
});


Report_Verticalbarchart_Js('Report_Horizontalbarchart_Js', {},{
	generateChartData : function() {
		if(ChartsWidget_ChartDetail_Js.isEmptyData()) {
			ChartsWidget_ChartDetail_Js.displayNoDataMessage();
			return false;
		}

		var jsonData = jQuery('input[name=data]').val();
		var data = this.data = JSON.parse(jsonData);
		var values = data['values'];

		var chartData = [];
		var yMaxValue = 0;

		if(data['type'] == 'singleBar') {
			for(var i in values) {
				var multiValue = values[i];
				chartData[i] = [];
				for(var j in multiValue) {
					chartData[i].push(multiValue[j]);
					chartData[i].push(parseInt(i)+1);
					if(multiValue[j] > yMaxValue){
						yMaxValue = multiValue[j];
					}
				}
			}
			chartData = [chartData];
		} else {
			chartData = [];
			chartData[0] = [];
			chartData[1] = [];
			chartData[2] = [];
			for(var i in values) {
				var multiValue = values[i];
				for(var j in multiValue) {
					chartData[j][i] = [];
					chartData[j][i].push(multiValue[j]);
					chartData[j][i].push(parseInt(i)+1);
					if(multiValue[j] > yMaxValue){
						yMaxValue = multiValue[j];
					}
				}
			}
		}
		yMaxValue = yMaxValue + (yMaxValue*0.15);

		return {'chartData':chartData,
				'yMaxValue':yMaxValue,
				'labels':data['labels'],
				'data_labels':data['data_labels'],
				'title' : data['graph_label']
			};

	},

	loadChart : function() {
		var data = this.generateChartData();
		var labels = data['labels'];

		jQuery.jqplot('chartcontent', data['chartData'], {
			title: data['title'],
			animate: !$.jqplot.use_excanvas,
            seriesDefaults: {
                renderer:$.jqplot.BarRenderer,
				showDataLabels: true,
                pointLabels: { show: true, location: 'e', edgeTolerance: -15 },
                shadowAngle: 135,
                rendererOptions: {
                    barDirection: 'horizontal'
                }
            },
            axes: {
                yaxis: {
					tickRenderer: jQuery.jqplot.CanvasAxisTickRenderer,
					renderer: jQuery.jqplot.CategoryAxisRenderer,
					ticks: labels,
					tickOptions: {
					  angle: -45
					}
                }
            },
			legend: {
                show: true,
                location: 'e',
                placement: 'outside',
				showSwatch : true,
				showLabels : true,
				labels:data['data_labels']
            }
        });
		jQuery('table.jqplot-table-legend').css('width','95px');
	},

	postInitializeCalls : function() {
		var thisInstance = this;
		this.getContainer().on("jqplotDataClick", function(ev, gridpos, datapos, neighbor, plot) {
			var linkUrl = thisInstance.data['links'][neighbor[1]-1];
			var numberOfClick = jQuery('input[name=numberOfClick]').val();
			if((linkUrl)&&(numberOfClick==1)) window.open(linkUrl);
			if(numberOfClick==2) numberOfClick=0;
			numberOfClick = parseInt(numberOfClick) + 1;
			jQuery('input[name=numberOfClick]').val(numberOfClick);
		});
		this.getContainer().on("jqplotDataHighlight", function(evt, seriesIndex, pointIndex, neighbor) {
			$('.jqplot-event-canvas').css( 'cursor', 'pointer' );
		});
		this.getContainer().on("jqplotDataUnhighlight", function(evt, seriesIndex, pointIndex, neighbor) {
			$('.jqplot-event-canvas').css( 'cursor', 'auto' );
		});
	}
});


Report_Verticalbarchart_Js('Report_Linechart_Js', {},{

	generateData : function() {
		if(ChartsWidget_ChartDetail_Js.isEmptyData()) {
			ChartsWidget_ChartDetail_Js.displayNoDataMessage();
			return false;
		}

		var jsonData = jQuery('input[name=data]').val();
		var data = this.data = JSON.parse(jsonData);
		var values = data['values'];

		var chartData = [];
		var yMaxValue = 0;

		chartData[1] = []
		chartData[2] = []
		chartData[0] = []
		for(var i in values) {
			var value =  values[i];
			for(var j in value) {
				chartData[j].push(value[j]);
			}
		}
		yMaxValue = yMaxValue + yMaxValue*0.15;

		return {'chartData':chartData,
				'yMaxValue':yMaxValue,
				'labels':data['labels'],
				'data_labels':data['data_labels'],
				'title' : data['graph_label']
			};
	},

	loadChart : function() {
		var data = this.generateData();
		var plot2 = $.jqplot('chartcontent', data['chartData'], {
			title: data['title'],
			legend:{
				show:true,
				labels:data['data_labels'],
				location:'ne',
				showSwatch : true,
				showLabels : true,
				placement: 'outside'
 	    	},
			seriesDefaults: {
				pointLabels: {
					show: true
				}
			},
			axes: {
				xaxis: {
					min:0,
					pad: 1,
					renderer: $.jqplot.CategoryAxisRenderer,
					ticks:data['labels'],
					tickOptions: {
						formatString: '%b %#d'
					}
				}
			},
			cursor: {
				show: true
			}
		});
		jQuery('table.jqplot-table-legend').css('width','95px');
	}
});
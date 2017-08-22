$(function () {
	
	
	var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar-holder').fullCalendar({
			header: {
				left: 'prev, next',
				center: 'title',
				right: 'month,basicWeek,basicDay,'
			},
			events: [
				{
					title: 'Long Event',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2),
					color: Beat.chartColors[2]
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, 19, 16, 0),
					end: new Date (y, m, 22, 16, 0),
					allDay: false,
					color: Beat.chartColors[2]
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d+4, 16, 0),
					end: new Date(y, m, d+6, 16, 0),
					allDay: false,
					color: Beat.chartColors[0]
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false,
					color: Beat.chartColors[2]
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false,
					color: Beat.chartColors[0]
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false,
					color: Beat.chartColors[2]
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: '../../../google.com/default.htm',
					color: Beat.chartColors[0]
				}
			]
		});
	
});
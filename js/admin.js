jQuery(document).ready(function(){

	// Add excerpt box above post editor 
	/*var $excerpt	= jQuery('#postexcerpt');
	var $wysiwyg	= jQuery('#postdivrich');

	$wysiwyg.prepend($excerpt);*/

	// Datepicker
	jQuery.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
            closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
            prevText: '<zurück', prevStatus: 'letzten Monat zeigen',
            nextText: 'Vor>', nextStatus: 'nächsten Monat zeigen',
            currentText: 'heute', currentStatus: '',
            monthNames: ['Januar','Februar','März','April','Mai','Juni',
            'Juli','August','September','Oktober','November','Dezember'],
            monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
            'Jul','Aug','Sep','Okt','Nov','Dez'],
            monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
            weekHeader: 'Wo', weekStatus: 'Woche des Monats',
            dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
            dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
            dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
            dateFormat: 'dd.mm.yy', firstDay: 1, 
            initStatus: 'Wähle ein Datum', isRTL: false};
    //jQuery.datepicker.setDefaults($.datepicker.regional['de']);

	jQuery(".datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd/mm/yy',
		firstDay: 1, // monday

		// The function receives the selected date as text and the datepicker instance as parameters
		onSelect: function(dateText, inst) {

			/*var input = jQuery(this),
				inputID = input.attr('id'),
				date = new Date(Date.parse(input.datepicker('getDate')));

			// Format English
			var suffix = ""
	        switch(inst.selectedDay) {
	            case '1': case '21': case '31': suffix = 'st'; break;
	            case '2': case '22': suffix = 'nd'; break;
	            case '3': case '23': suffix = 'rd'; break;
	            default: suffix = 'th';
	        }

	        var date_en_1 = jQuery.datepicker.formatDate("DD d", date),
	        	date_en_2 = jQuery.datepicker.formatDate(" 'of' MM — yy", date);

			jQuery('#'+inputID+'_en').val(date_en_1 + suffix + date_en_2);

			// Format Deutsch
			var date_de = jQuery.datepicker.formatDate( "DD, 'den' d. MM — yy", date, {
								dayNamesShort: jQuery.datepicker.regional[ "de" ].dayNamesShort,
								dayNames: jQuery.datepicker.regional[ "de" ].dayNames,
								monthNamesShort: jQuery.datepicker.regional[ "de" ].monthNamesShort,
								monthNames: jQuery.datepicker.regional[ "de" ].monthNames
							});

			jQuery('#'+inputID+'_de').val(date_de);*/
		}
	});
});
$(document).ready(function() {
	$('#star').raty({
		score: function() {
			return $(this).attr('data-score');
		},
		click: function(score, evt) {
			$("#jiwen_commentbundle_commenttype_score").val(score);
		},
		scoreName: 'jiwen_commentbundle_commenttype[score]',
		starOff: '/Resources/assets/jiwen/images/star-off.png',
		starOn: '/Resources/assets/jiwen/images/star-on.png'
	});
})

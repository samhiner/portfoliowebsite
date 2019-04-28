//everything in this function switches the page on tab click

var tabsList = ['projects', 'achievements'];

$(function() {
	function tabChange(oldTab, newTab) {
		if (oldTab != 'FIRST') {
			document.getElementById(oldTab + 'Link').style.backgroundColor = '';
		}

		document.getElementById(newTab + 'Link').style.backgroundColor = '#001064';
	}

	//find difference between old tab and current tab and move every page a proportional distance
	function pageChange(nowPage, nextPage) {
		var oldPage = nowPage !== undefined ? nowPage : 'FIRST';
		var oldPage = oldPage[0] == '#' ? oldPage.substring(1) : oldPage;
		var newPage = nextPage || window.location.hash;

		//home is '#' or '', which is not in list so is set to -1.
			//this way projects = home + 1, achievements = projects + 1, etc
		//slice(1) takes off the # that is in front of that part of url
		var tabDistance = tabsList.indexOf(newPage.slice(1)) - tabsList.indexOf(oldPage);
		var tabPercent = '-=' + String(tabDistance * 100) + '%';

		//if you directly navigate to url oldPage is first and there is no animation
		if (oldPage == 'FIRST') {
			delay = 0;
		} else {
			delay = 750;
		}

		$('#homePage').animate({marginLeft: tabPercent}, delay);
		for (var x = 0; x < tabsList.length; x++) {
			$('#' + tabsList[x] + 'Page').animate({marginLeft: tabPercent}, delay);
		}
		tabChange(oldPage, newPage.substring(1));
	}

	//on start change the tab to the one matching your url and set as current url
	pageChange();
	window.currentPage = window.location.hash;

	function tabSwitch() {
		//switch string is a placeholder so it isn't seen as empty and doesn't get set to FIRST
		pageChange(window.currentPage, window.location.hash);
		window.currentPage = window.location.hash;
	}

	//whenever hash changes (you navigate to new jQuery element) change tab
	window.onhashchange = tabSwitch;

});

function popupOn(id) {
	document.getElementById('darkScreen').style.display = 'block';
	document.getElementById(id).style.display = 'block';
}

//makes all popups invisible (so you can just click the darkScreen w/o it needing to know what is on)
function popupOff() {
	document.getElementById('darkScreen').style.display = 'none';
	
	allPopups = document.getElementsByClassName('popup');
	for (var x = 0; x < allPopups.length; x++) {
		allPopups[x].style.display = 'none';
	}
}

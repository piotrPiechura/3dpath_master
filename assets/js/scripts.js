// ustalenie kilku zminnych

var aDataTables = [ ];       // tablica na obiekty tabel
var aChbValues = [];
var aDialogs = [];

var iDefaultTop = 100;
var iDefaultLeft = 150;

var iDefaultDialogWidth = 560;
var iDefaultDialogHeight = 'auto';
// document ready
$(document).ready(function() {
	fnDisappearingContent();
	$('.buttons').buttonset();


	$(".tabs").tabs();
	//$('.tooltip').tooltipster();

	fnDatePicker('');
	fnSelectBoxItAndFix('select');

	$('#topBar .online').mouseenter(function(){
		$('#loggedIn').dequeue().fadeIn(300);
	});

	$('#topBar .online').mouseleave(function () {
		$('#loggedIn').dequeue().fadeOut(300);
	});
	$(document).bind('drop dragover', function (e) {
		e.preventDefault();
	});        
});



function fnSelectBoxItAndFix(sSelector){
	//$(sSelector).selectBoxIt({ autoWidth: false});
	fnFixSelect();
}


function fnFixSelect(sSelector){
	$(sSelector+' .selectboxit-container ul').click(function(){
		$('.selectboxit-btn.selectboxit-enabled').blur();
	})
}

function clearInput(s){
	$(s).parent().find('input').val('');
	$(s).parent().find('input').keyup();
	$(s).parent().find('input').blur();
	$(s).hide();
}

function fnRunEffect(eff, t, opt) {
  // most effect types need no options passed by default
  var options, time;
  if(typeof opt === "undefined")
    options = {};
  else
    options = opt;
  if(typeof time === "undefined")
    time = 500;
  else
    time = t;
  console.log(options);
  console.log(time);
  //if ( eff === "scale" ) {
    //options = { percent: 0 };
   //else if ( eff === "size" ) {
    //options = { to: { width: 200, height: 60 } };

  $( "#isDiscount" ).effect( eff, options, 5000, callback );
};

function callback() {
  setTimeout(function() {
    $( "#isDiscount" ).removeAttr( "style" ).hide().fadeIn();
  }, 1000 );
};


$(function(){
    $(document).on('click', 'div.magnificationWrap', function(){
        $(this).find('.magnification').toggle();
    });
    /*
    $(document).on('mouseover', 'div.magnificationWrapHover', function(){
        $(this).find('.magnification').toggle();
    });
    */
});



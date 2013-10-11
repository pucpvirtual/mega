
$(document).on('ready',function(){
	$('#mform1').on('submit',verified);	
	$('#id_type').on('change',choicetype);	
	$('#id_wrapcat').on('change','select.selectanid',anida);
	$('#id_wrapcat').on('change','#course',getdata);
	hideall(null);
});


var verified = function verified(e){

	var _t = $('#id_type').val();
	var _e = $('#element').val();
	var _ct = $('select[name=category]:last').val();
	var _c = $('#course').val();
	var _n = $('#id_name').val().trim();

	switch(_t){

		case '0':
			e.preventDefault();
			alert('Debe seleccionar un tipo^^');
			break;

		case '1':
			if(_c != undefined && _ct == 0) break;

			if(_ct == 0 ){
				e.preventDefault();
				alert('Debe seleccionar un tipo^^');
				break;
			}
			if(_c == undefined){
				e.preventDefault();
				alert('La categoria que ha seleccionada no tiene cursos^^');
				break;				
			}
			break;

		case '2':
			if(_e == undefined){
				e.preventDefault();
				alert('El curso seleccionado no posee Examenes^^');
				break;
			}
			if(_e == 0){
				e.preventDefault();
				alert('Debe seleccionar un examen^^');
				break;
			}
			break;

		case '3':
			if(_e == undefined){
				e.preventDefault();
				alert('El curso seleccionado no posee Scorms^^');
				break;
			}
			if(_e == 0){
				e.preventDefault();
				alert('Debe seleccionad un scorm^^');
				break;
			}
				
			break;

		default:
			e.preventDefault();
			break;
	}

	if( _n == ""){
		e.preventDefault();
		alert('Debe ingresar un nombre')
	}

}

var anida = function anida(){
	e = $(this);

	var url = 'nextform.php';
	var datos = { 'cat': e.val() };

    $.post(url, datos, function(result) {  
    	 $($(e.attr('rel'))).html('');  
	    $($(e.attr('rel'))).html(result);  
  	}); 
  	getdata();

}

var getdata = function getdata(){

	var url = 'nextform.php';
	var course = $('#course').val();
	var type = $('#id_type').val();
	var cat =$('select[name=category]:last').val();
	course = (course == undefined)? '0' : course;
	var datos = {'course':course, 'type':type, 'category' : cat};

	switch(type)
	{
		case '1':
			var element = '.wapcontentcourse'
			break;
		case '2':
			var element = '.wapcontentexam'
			break;
		case '3':
			var element = '.wapcontentscorm'
			break;
		default:
			return;
			break;
	}
		$('.wrapdata').html('');  
	    $.post(url, datos, function(result) {  
		    $(element).html(result);  
	  	}); 		

}

var hideall = function hideall(ob){

	$('#id_wrapscorm').css({'display':'none'});
	$('#id_wrapexam').css({'display':'none'});
	$('#id_wrappoll').css({'display':'none'});

	var _i = $(ob);

	if(_i.length > 0){
		_i.css({'display':'block'});
	}

}
     
var choicetype  = function choicetype(e){

	var _o = $('#id_type');

	switch(_o.val()){
		case '0': 
			hideall(null);
			break;
		case '1':
			hideall(null);
			getdata();
			break;
		case '2': 
			hideall('#id_wrapexam');
			getdata();
			break;
		case '3': 
			hideall('#id_wrapscorm');
			getdata();
			break;
		default:
			hideall(null);
			break;
	}

}   
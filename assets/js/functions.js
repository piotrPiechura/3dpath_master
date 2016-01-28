/**
 * Funkcja łądujące text z id_of_input_with_text i zwracająca wynenerowany na podstawie texu wolny url
 * nastęnie wygenerowany url jest dodowany do inputa o id targer_input_id
 * zmienna id_of_ignore_url przechowuje id urli które powinny być ingorowane przy sprawdzaniu powtarzalności (bo np są już przypisane do danej np. kategorii)
 * @param {String} id_of_input_with_text
 * @param {String} targer_input_id
 * @param {String} id_of_ignore_url
 */
function fnGenereteNewFreeUrl(post_url, id_of_input_with_text,targer_input_id,id_of_ignore_url,uniq_var_name_to_save_last_correct_url, add_prefix){
    var url = $('#'+id_of_input_with_text).val();
    if(url=='') return;

    if(typeof add_prefix !== 'undefined'){
        url = add_prefix + url;
    }

    $.ajax({
        type: "POST",
        data: {
            name:url,
            ignore_id:id_of_ignore_url
        },
        url: base + post_url,
        success: function(loaded_json) {
            json = jQuery.parseJSON( loaded_json );
            $('#'+targer_input_id).val(json.new_free_url);
        }
    });
}
function fnGenereteNewCode(hidden_code_, code_){

    var hidden = $('#'+hidden_code_).val();
    var open = $('#'+code_).val(hidden);

}
/**
 * Funkcja updatejące tablice DataTable o id=table_id w trochę "brzydki" sposób
 * @param {String} table_id
 */
function reload_table(table_id){
    aDataTables[table_id].fnStandingRedraw(null);
}


function addZeroIfNeeded(val){
    val = "" + val; if(val.length === 1) val = "0" + val;
    return val;
}

/**
 * Dodaje do input textów znikanie ich domyślenj wartości po
 * kliknięciu oraz jej powrót przy pozostawieniu pustego pola
 */
function fnDisappearingContent() {
    $('input.disappearingContent').each(function() {
        $(this).bind("focus", function(){
            if(this.value == this.defaultValue){
                this.value='';
                $(this).removeClass('grey');
            }
        });
    });
    $('input.disappearingContent').each(function(){
        $(this).bind("blur", function(){
            if(this.value==''){
                this.value=this.defaultValue;
                $(this).addClass('grey');
            }
        });
    });
}

/**
 * fnCallback triggers function stored as object
 * callback {
 *      method: existing_method_name 
 *      params: array of parameters 
 * }
 * @param {object} callback
 * @returns {undefined}
 */
function fnCallback( callback ){
    var func = "" + callback.method + "(";
    var prms = "";

    if(typeof callback.params !== 'undefined'){
        $.each(callback.params, function( index, value ) {
            var v = ((value - 0) == value && value.length > 0) ? value : "'" + value + "'";
            func += v + ","
        });
        func = func.substring(0, func.length - 1);
    }
    func += ")";
    try {
        eval(func);
    }catch(e){
        console.log(e);
    }


    var func2 = "" + callback.method + "(";
    var prms = "";

    if(typeof callback.params2 !== 'undefined'){
        $.each(callback.params2, function( index, value ) {
            var v = ((value - 0) == value && value.length > 0) ? value : "'" + value + "'";
            func2 += v + ","
        });
        func2 = func2.substring(0, func2.length - 1);
    }
    func2 += ")";
    try {
        eval(func2);
    }catch(e2){
        console.log(e2);
    }



}


/**
 * Wykonuje akcje masową na zaznaczonych elementach
 * @param sTableId      nazwa zbioru danych
 * @param sSource       ściezka do pliku
 * @param sMsg          Nazwa akcji "user friendly". Uzywana w przypadku błędu. "Nie udało sie wykonać sMsg"
 * @param asKeyValue    klucz => wartość

 */
function fnMassAction(sSource, asKeyValue, sTableId, sMsg , bReadChb){
    var aaData = {};
    if(bReadChb == true || bReadChb == undefined){
        aaData = fnMassActionCollectData(sTableId, asKeyValue);
    }else{
        aaData = asKeyValue;
    }
    var oResponse;

    fnShowLoader();
    oResponse = $.post(sSource, aaData, function (json) {                             // przesyłamy
            fnHideLoader();
            fnCheckJsonLogin(json);
            fnJsonIfSuccess(sTableId, json);
            fnJsonIfError(json);
            aDataTables[sTableId].fnStandingRedraw(null);                               // przeładowanie zawartości tabeli zachowujące paginacje itp
        }, "json"
    );
    oResponse.error(function() {
        fnConnectionError(sTableId,sMsg)
        aDataTables[sTableId].fnStandingRedraw();
    });
}
/**
 * Pobiera id z zaznaczonych checkboxów z tabeli i łaczy jest z argumentem
 * @param sTableId     id tabeli
 * @param asKeyValue   argumenty
 * @returns {Object}
 */
function fnMassActionCollectData(sTableId, asKeyValue){
    var aaData={};                                           // tworzymy tablice zawierającą id wybranch pozycji
    aaData.values = {};
    $('#' + sTableId +' .checkboxId').each(function(){
        if($(this).prop('checked')){
            aaData.values[$(this).val()] = asKeyValue;
        }
    });
    aaData.ajax = true;
    return aaData;
}
/**
 * Sprawdza czy json zwrócony przez serwer informuje o sukcesie czy o porażce
 * @param json      dostarczona do funkcji odpowiedź serwera json
 * @returns true/false w zalżności czy sukces czy porażka
 */
function fnCheckJsonIsSuccess(json){
    if( json.ok.status == true) return true;
    else                        return false;
}
/**
 * Wykonuje działanie serwer zwróci sukces przez json
 * @param sTableId  id tabeli
 * @param json      odpowiedź serwera json
 */
function fnJsonIfSuccess(sTableId, json){
    if( json.ok.status == true){
        $.jGrowl( json.ok.message, { group: 'alertOk' });                       // komunikat
        fnSaveCheckboxesValues( sTableId);
        return true;
    }
    return false;
}


/**
 * Wykonuje działanie  jeśli serwer zwróci błąd przez json
 * @param json
 */
function fnJsonIfError(json){
    if(json.error.status == true){
        $.jGrowl(json.error.message , { group: 'alertError' });
        return true;
    }
    return false;
}
/**
 * ukrywa loader i wyświetla bląd
 * @param sMsg
 */
function fnConnectionError(sTableId, sMsg){
    fnHideLoader();
    $.jGrowl('Nie udało się wykonać:  '+ sMsg + '<br />Błąd połączenia', { sticky: true, group: 'alertError' });
    //aDataTables[sTableId].fnStandingRedraw();
}



/**
 * Sprawdza czy użytkownik dalej jest zalogowany, jeśli nie to przeładowywuje storne (kieruje na logowanie)
 * @param json
 */
function fnCheckJsonLogin(json){
    if( json.login == false){
        window.location.reload();
    }
    return 0;
}



/**
 * Wykonuje akcje masową na zaznaczonych elementach
 * @param sTableId      nazwa zbioru danych
 * @param sSource       ściezka do pliku
 * @param sMsg          Nazwa akcji "user friendly". Uzywana w przypadku błędu. "Nie udało sie wykonać sMsg"
 * @param aaArgs        tablica argumentów
 */
function fnInTableAction(sSource, aaArgs, sTableId, sMsg){
    fnShowLoader();
    var aaData={};                                           // tworzymy tablice zawierającą id wybranch pozycji
    aaData.values = aaArgs;

    aaData.ajax = true;
    oResponse = $.post( sSource, aaData, function(json){                             // przesyłamy
            fnHideLoader();
            fnCheckJsonLogin(json);
            fnJsonIfSuccess(sTableId, json);
            fnJsonIfError(json);
            aDataTables[sTableId].fnStandingRedraw();
        },"json"
    );
    oResponse.error(function() {
        fnConnectionError(sTableId,sMsg);
        aDataTables[sTableId].fnStandingRedraw();
    });
}

/**
 * funkcja wykonuje prostą akcje
 * @param sSource   adres
 * @param aArgs     argmenty
 * @param sTableId  id tabeli
 * @param sMsg      wiadomość jeśli połaczenie się nie uda
 */
function fnSimpleAction(sSource , aArgs , sTableId ,sMsg, _fnCallback){
    fnShowLoader();
    var aaData={};

    aaData.values = aArgs;
    aaData.ajax = true;
    oResponse = $.post(sSource, aaData, function (json) {                            // przesyłamy
            fnCheckJsonLogin(json);
            fnJsonIfSuccess(sTableId, json);
            fnJsonIfError(json);
            if(sTableId !== null) {
                aDataTables[sTableId].fnStandingRedraw();
            }
            fnHideLoader();
            if(_fnCallback !== undefined){
                _fnCallback(aArgs, json, sTableId);
            }
            try{

                if(typeof json !== 'undefined' && json != null){
                    if(typeof json.callback !== 'undefined' && typeof json.callback.method !== 'undefined'){

                        fnCallback(json.callback);
                    }
                }
            }catch(e){
                console.log(e);
            }

        }, "json"
    );
    oResponse.error(function () {
        fnConnectionError(sTableId, sMsg);
    });
}
//jednorazowa wersja funkcji specjalnie dla przycisku włacz/wyłącz maintenance  
//nie używać tej funkcji bo kiedyś mozę zostac usunięta
function fnSimpleActionMaintenanceVersion(sSource , aArgs , sTableId , sMsg , buttonOnId , buttonOffId){
    fnShowLoader();
    var aaData={};

    aaData.values = aArgs;
    aaData.ajax = true;
    oResponse = $.post(sSource, aaData, function (json) {                            // przesyłamy
            fnCheckJsonLogin(json);
            if(fnCheckJsonIsSuccess(json)){
                fnJsonIfSuccess(sTableId, json);
            }
            else{
                fnReloadMaintenanceOnOffButtonsActivity(json,buttonOnId,buttonOffId);
                fnJsonIfError(json);
            }
            fnHideLoader();
        }, "json"
    );
    oResponse.error(function () {
        fnConnectionError(sTableId, sMsg);
    });
}
//jednorazowa wersja funkcji specjalnie dla przycisku włacz/wyłącz maintenance  
//nie używać tej funkcji bo kiedyś mozę zostac usunięta
function fnReloadMaintenanceOnOffButtonsActivity(json , buttonOnId , buttonOffId){
    if(json.turn_on_before_action == 1){//włączone przed akcją która skończyła się niepowodzeniem
        if(!$('#'+buttonOnId).prev().hasClass('ui-state-active'))//jesli ON nieaktywne
            $('#'+buttonOnId).prev().addClass('ui-state-active');//aktywuj
        if($('#'+buttonOffId).prev().hasClass('ui-state-active'))//jeśli OFF aktynwy
            $('#'+buttonOffId).prev().removeClass('ui-state-active');//deaktywuj
    }
    else if(json.turn_on_before_action == 0){//wyłączone przed akcją która skończyła się niepowodzeniem
        if($('#'+buttonOnId).prev().hasClass('ui-state-active'))//jesli ON aktywne
            $('#'+buttonOnId).prev().removeClass('ui-state-active');//deaktywuj
        if(!$('#'+buttonOffId).prev().hasClass('ui-state-active'))//jeśli OFF nieaktynwy
            $('#'+buttonOffId).prev().addClass('ui-state-active');//aktywuj
    }
}

/**
 *
 * @param event
 * @param sSource
 * @param aArgs
 * @param sTableId
 * @param sMsg
 */
function fnSimpleActionPricing(event, sSource , aArgs , sTableId ,sMsg){
    fnShowLoader();
    var aaData={};
    aaData.values = aArgs;
    aaData.ajax = true;

    oResponse = $.post(sSource, aaData, function (json) {                            // przesyłamy
            fnHideLoader();
            fnCheckJsonLogin(json);
            if(fnJsonIfSuccess(sTableId, json)){
                fnPricingOk(event, sTableId, aArgs);
            }
            if(fnJsonIfError(json)){
                fnPricingError(event);
            };

        }, "json"
    );
    oResponse.error(function () {
        fnConnectionError(sTableId, sMsg);
    });
}

/**
 * Znajduje TD będące rodzicem event.target
 * @param       event
 * @returns     {HTMLElement}
 */
function fnFindTD(event){
    var oSender = $(event.target)
    while( oSender.prop('tagName') != 'TD'){
        oSender = $(oSender).parent();
    }
    return oSender;
}

/**
 * Wykonuje proste akcji jak usuń, włącz/wyłącz itp
 * @param asId          Id rekordu
 *                      null - dla akcji nie wymagającej podania id
 *                      [] - dla akcji która ma pobrać id z checkboxów w tabeli
 *                      [1, 2, 3] - dla akcji o id zdefiniowanych w wywołaniu
 * @param sTableId      Określenie zbioru danych
 * @param sSource       ściezka do pliku
 * @param sMsg          Nazwa akcji "user friendly". Uzywana w przypadku błędu. "Nie udało sie wykonać sMsg"
 * @param sTitle        Tytuł okna zapytania
 * @param sQuestion     Tekst okna zapytania
 */
function fnSimpleActionConfirm(sSource, asId, sTableId, sMsg, sTitle, sQuestion, iZindex){
    var aaData;

    iZindex = (typeof iZindex !== 'undefined')? iZindex : false;
    aaData = fnSimpleActionCollectData(sTableId, asId);
    if(aaData != 0){
        fnPrepareConfirmDialog(sTitle, sQuestion);

        var dialogConfirm;
        dialogConfirm = $("#dialogConfirm").dialog({
            open: function (event, ui) {
                $('#dialogConfirm').parent().find('.ui-dialog-titlebar-close').remove();
                $('#dialogConfirmBtnOk').click(function () {
                    fnShowLoader();
                    aaData.ajax = true;
                    oResponse = $.post(sSource, aaData, function (json) {                            // przesyłamy
                            fnHideLoader();
                            fnCheckJsonLogin(json);
                            fnJsonIfSuccess(sTableId, json);
                            fnJsonIfError(json);
                            aDataTables[sTableId].fnStandingRedraw(null);




                            try{
                                if(typeof json !== 'undefined' && json != null){


                                    if(typeof json.callback !== 'undefined' && typeof json.callback.method !== 'undefined'){
                                        fnCallback(json.callback);
                                    }
                                }
                            }catch(e){
                                console.log(e);
                            }

                        }, "json"
                    );
                    oResponse.error(function () {
                        fnConnectionError(sTableId,sMsg);
                    });
                    fnClosePopUp(dialogConfirm, false)
                });
                $('#dialogConfirmBtnCancel').click(function () {
                    fnClosePopUp(dialogConfirm, false)
                });
            },
            resizable: false,
            modal: true,
            minHeight: '100px',
            closeOnEscape: true
        });

        if(iZindex !== false){

            var css = dialogConfirm.parent().attr('style');
            dialogConfirm.parent().attr('style', css + ' z-index: ' + iZindex + ';');
        }
    }
}

/**
 * Zbiera dane dla prostej akcji, jeśli nie przekazano ich w wywołaniu
 * @param sTableId  id tabeli
 * @param asId      argumentu
 * @returns {Object}
 */
function fnSimpleActionCollectData(sTableId, asId){
    var aaData = {};
    if(asId != null){
        if($(asId).size() == 0){
            $('#' + sTableId +' .checkboxId').each(function(){
                if($(this).prop('checked')){
                    asId.push($(this).val());
                }
            })
        }
        if($(asId).size() == 0){
            $.jGrowl(__lang('Najpierw zaznacz rekord'), { group: 'alertInfo' });

            return 0;
        }
        aaData.ids = asId;
    }
    return aaData;
}
/**
 * Przygotowywuje dialog pootwierdzenia
 * @param sTitle    tytuł okna
 * @param sQuestion treść okna
 */
function fnPrepareConfirmDialog(sTitle, sQuestion){
    $('#dialogConfirmBtnOk').unbind("click");
    $('#dialogConfirmBtnCancel').unbind("click");
    $('#dialogConfirm').prop('title', sTitle);
    $('#dialogConfirmAdd').prop('title', sTitle);
    $('#dialogConfirmSave').prop('title', sTitle);

    $('#dialogConfirm').find('.question').html(sQuestion);
    $('#dialogConfirmAdd').find('.question').html(sQuestion);
    $('#dialogConfirmSave').find('.question').html(sQuestion);
}
function fnPrepareConfirmDialogDuplication(sTitle, sQuestion){
    $('#dialogConfirmDuplicationBtnOk').unbind("click");
    $('#dialogConfirmDuplicationBtnCancel').unbind("click");
    $('#dialogConfirmDuplication').prop('title', sTitle);

    $('#dialogConfirmDuplication').find('.question').html(sQuestion);
}



/**
 * Try to load editor
 * @returns null
 */
function tryLoadCodeMirror(){
    if($('div.popupAdd').length){
        var cm_o = $('div.popupAdd').find('.html-code-textarea');
        if(typeof cm_o !== 'undefined' && cm_o.length)
            cm_o.each(function(i, o){
                var editor = CodeMirror.fromTextArea(document.getElementById($(o).attr('id')), {
                    mode: 'text/html',
                    autoCloseTags: true,
                    styleActiveLine: true,
                    lineNumbers: true,
                    lineWrapping: true,
                    //  autofocus: true,
                });
                $(o).data('CodeMirrorInstance', editor);
            });
    }
    return null;
}



/**
 * Rozpoczyna złozoną akcje jak np edycja, dodanie itp.
 * Akcja wykonywana jest w nowym popupie, którego zawartość ładowana jest dynamicznie przez ajax.
 * funkcja jedynie inicjuje popupa w którym wykonywana jest akcja, a nie wyk
 * @param asId          Id rekordu
 *                      null - dla akcji nie wymagającej podania id
 *                      [] - dla akcji która ma pobrać id z checkboxów w tabeli
 *                      [1, 2, 3] - dla akcji o id zdefiniowanych w wywołaniu
 * @param sTableId      Określenie zbioru danych
 * @param sSource       ściezka do pliku
 * @param sMsg          Nazwa akcji "user friendly". Uzywana w przypadku błędu. "Nie udało sie wykonać sMsg"
 * @param iWidth        szerokość okna
 * @param iHeight       wysokość okna
 * @param sTitle        tytuł okna
 */
function fnComplexActionPopup(sSource, asId ,sTableId, sTitle, iWidth, iHeight, sMsg, sParent, bModal, iZindex){
    console.log(sSource);
    if(!sParent) sParent = null;
    var aaData = fnComplexActionPopupCollectData(sTableId, asId);
    if( aaData == false){
        return 0;
    }
    fnShowLoader();
    $.ajax({
        url: sSource,
        data: aaData,
        type: 'POST',
        success: function(sData){

            try {
                var json = $.parseJSON(sData);
                if(json.error.status == true){
                    $.jGrowl( json.error.message, { group: 'alertError' });
                    fnHideLoader();
                    return;
                }
            }catch(e){}

            fnHideLoader();
            var dialog;
            dialog = fnCreateDialog(sData, null, sSource, sTableId, sTitle, iWidth, iHeight, sMsg, sParent, bModal, iZindex);
            if(dialog == null){
                return;
            }
            fnLoadDialog(dialog);
            tryLoadCodeMirror();

            return dialog;
        }
    });
    return;
}


/**
 * Akcja wykonywana jest w nowym popupie, którego zawartość ładowana jest dynamicznie przez ajax.
 * funkcja jedynie inicjuje popupa w którym wykonywana jest akcja, a nie wyk
 * @param asId          Id rekordu
 *                      null - dla akcji nie wymagającej podania id
 *                      [] - dla akcji która ma pobrać id z checkboxów w tabeli
 *                      [1, 2, 3] - dla akcji o id zdefiniowanych w wywołaniu
 * @param sTableId      Określenie zbioru danych
 * @param sSource       ściezka do pliku
 * @param sMsg          Nazwa akcji "user friendly". Uzywana w przypadku błędu. "Nie udało sie wykonać sMsg"
 * @param iWidth        szerokość okna
 * @param iHeight       wysokość okna
 * @param sTitle        tytuł okna
 */
function fnComplexActionPopup2(sSource, asId ,sTableId, sTitle, iWidth, iHeight, sMsg, fnOnClose, fnOnSave){
    var aaData = fnComplexActionPopupCollectData(sTableId, asId);
    if( aaData == false){
        return 0;
    }
    fnShowLoader();
    $.ajax({
        url: sSource,
        data: aaData,
        type: 'POST',
        success: function(sData){
            fnHideLoader();
            var dialog;
            dialog = fnCreateDialog(sData, null, sSource, sTableId, sTitle, iWidth, iHeight, sMsg, null);
            if(dialog == null){
                return;
            }
            fnLoadDialog2(dialog);
            if(typeof fnOnClose!== "undefined" )
                dialog.find('.button.close').unbind("click").click(function(){fnOnClose(dialog)});
            if(typeof fnOnSave!== "undefined" )
                dialog.find('.button.dialogBtnSave').unbind("click").click(function(){fnOnSave(dialog)});
        }
    });
    return;
}

/**
 * Akcja wykonywana jest w nowym popupie, którego zawartość ładowana jest dynamicznie przez ajax.
 *
 * Dostępne opcje:
 * source, id , tableId, title, width, height, msg, onClose, onSave, data
 * onSave - akcja po zapisaniu zamiast domyślnej (zapisywanie popupa)
 * onSaveExtra - dodatkowa akcja po zapisaniu
 */
function fnComplexActionPopup3(args){
    var requiredProp = ['source'];
    for(var i=0; i<requiredProp.length; i++) {
        if(!args.hasOwnProperty(requiredProp[i])) { // pola wymagane
            alert('Nie podano parametru: '+requiredProp[i]);
        }
    }

    var data = fnComplexActionPopupCollectData(args.tableId, args.id);

    fnShowLoader();
    $.ajax({
        url: args.source,
        data: data,
        type: 'POST',
        success: function(sData){
            fnHideLoader();
            var dialog;
            dialog = fnCreateDialog(sData, null, args.source, args.tableId, args.title, args.width, args.height, args.msg, null);
            if(dialog == null){
                return;
            }
            fnLoadDialog2(dialog);

            if(args.hasOwnProperty('onClose'))
                dialog.find('.button.close').unbind("click").click(function(){args.onClose(dialog)});
            if(args.hasOwnProperty('onSave')) {
                dialog.find('.button.dialogBtnSave').unbind("click").click(function(){args.onSave(dialog)});
            } else {
                $(dialog).find('.dialogBtnSave').click(function(){
                    var data2 = fnReadDialogInputs(dialog);
                    fnShowLoader();
                    oResponse = $.post( dialog.values.sSource, data2,
                        function(sData){
                            dialog.values.sData = sData;
                            fnHideLoader();
                            fnSetDialogContent(dialog)

                        },"html"
                    );
                    oResponse.error(function() {
                        fnConnectionError(dialog.values.TableId, dialog.values.sMsg);
                        fnHideLoader();
                    });
                });
                if(args.hasOwnProperty('onSaveExtra')) {
                    dialog.find('.button.dialogBtnSave').click(function(){args.onSave(dialog)});
                }
            }
        }
    });
    return;
}

/**
 * Zbiera dane dla akscji złożonej
 * @param sTableId
 * @param asId
 * @returns {*}
 */
function fnComplexActionPopupCollectData(sTableId, asId){
    var aaData;
    aaData = {};
    if(asId != null){
        if($(asId).size() == 0){
            asId = fnReadCheckboxes(sTableId);
        }
        if($(asId).size() > 1){
            $.jGrowl('Akcja możliwa tylko dla jednego rekordu', { group: 'alertInfo' });
        }
        if($(asId).size() == 0){
            $.jGrowl(__lang('Najpierw zaznacz rekord'), { group: 'alertInfo' });
            return false;
        }
        aaData.id = asId[0];
    }
    aaData.ajax = true;
    return aaData;
}

function fnLoadDialog2(dialog) {
    fnPrepareDialog(dialog);
    $(dialog).find('input.pretty').prettyCheckable({
        color: 'red'
    });
    $(dialog).find('.button.close').unbind("click");
    dialog.find('.button.close').click(function(){
        fnClosePopUp(dialog, true);
    });
}


function updateWidgetsPositions(data){

    var ajax = {};
    ajax.data = data;

    $("#save_status").css('background-image','url("' + base + 'images/loading_small.gif")');$("#save_status").text('Zapisuję...');

    oResponse = $.post(base + "cms/landingpage/updateWidgetPositions", ajax,
        function(sData){
            if(sData.error.status == true){
                $("#save_status").css('background-image','url("' + base + 'images/tick-red.png")');$("#save_status").text('Błąd zapisu!');
            }else{
                $("#save_status").css('background-image','url("' + base + 'images/tick.png")'); $("#save_status").text('Saved.');
            }
        },"json"
    );
    oResponse.error(function() {
        $("#save_status").css('background-image','url("' + base + 'images/tick-red.png")');
        $("#save_status").text('Błąd połączenia!');
    });
}


function remove_widget_by_id(id){
    var ajax = {};
    ajax.data = id;
    $("#save_status").css('background-image','url("' + base + 'images/loading_small.gif")');$("#save_status").text('Usuwam...');
    oResponse = $.post(base + "cms/landingpage/removeWidget", ajax,
        function(sData){
            if(sData.ok.status == true){
                $("#save_status").css('background-image','url("' + base + 'images/tick.png")'); $("#save_status").text('Usunięto.');
            }else{
                $("#save_status").css('background-image','url("' + base + 'images/tick-red.png")');$("#save_status").text('Błąd usuwania!');
            }
        },"json"
    );
    oResponse.error(function() {
        $("#save_status").css('background-image','url("' + base + 'images/tick-red.png")');
        $("#save_status").text('Błąd połączenia!');
    });

}

/**
 * Add new widget
 * @param gridster_el
 * @param xthml
 * @param width
 * @param height
 */
function append_new_widget(gridster_el, xthml, width, height, x, y, update){
    if(!update) update = false;
    if($(gridster_el).length){
        strips = stripslashes(xthml);
        gridster = $(gridster_el).gridster().data('gridster');
        if(!x) x = null; if(!y) y = null;
        gridster.add_widget(strips, width, height, x, y);
        if(update === true){
            updateWidgetsPositions(gridster.serialize());
        }
    }
}

/**
 * Remove widget
 * @param gridster_el
 * @param el
 */
function remove_widget(gridster_el, el){
    if($(gridster_el).length){
        gridster = $(gridster_el).gridster().data('gridster');
        id = el.attr("id");
        gridster.remove_widget( el );
        remove_widget_by_id(id);
    }
}


/**
 * rozpoczyna działanie okienka
 * @param dialog obiekt okna
 */
function fnLoadDialog(dialog) {
    fnPrepareDialog(dialog);
    $(dialog).find('input.pretty').prettyCheckable({
        color: 'red'
    });
    $(dialog).find('.dialogBtnSave').click(function(){
        fnShowLoader();
        aaData = fnReadDialogInputs(dialog);
        oResponse = $.post( dialog.values.sSource, aaData,
            function(sData){
                dialog.values.sData = sData;
                fnHideLoader();
                fnSetDialogContent(dialog);

                try{
                    var json = $.parseJSON(sData);
                    if(typeof json !== 'undefined' && json != null){

                        if(typeof json.callback !== 'undefined' && typeof json.callback.method !== 'undefined'){
                            fnCallback(json.callback);
                        }
                    }
                }catch(e){
                    console.log(e);
                }

            },"html"
        );
        oResponse.error(function() {
            fnConnectionError(dialog.values.TableId, dialog.values.sMsg);
            fnHideLoader();
        });
    });

    $(dialog).find('.dialogBtnSaveAndAdd').click(function(){
        fnShowLoader();
        aaData = fnReadDialogInputs(dialog);
        oResponse = $.post( dialog.values.sSource, aaData,
            function(sData){
                dialog.values.sData = sData;
                fnHideLoader();
                obj = JSON.parse(sData);

                if(obj.widget.add == true)
                    append_new_widget(".gridster ul", obj.widget.view, obj.widget.width, obj.widget.height, false, false, true);
                else{
                    //reload_lp();
                }
                fnSetDialogContent(dialog)
            },"html"
        );
        oResponse.error(function() {
            fnConnectionError(dialog.values.TableId, dialog.values.sMsg);
            fnHideLoader();
        });
    });

    dialog.find('.button.close').unbind("click");
    dialog.find('.button.close').click(function(){
        fnClosePopUp(dialog, true);
    });

}


function fnReadDialogInputs(dialog){
    var aaData = {};
    $(dialog).find('input, select, textarea').each(function(){
        if($(this).prop('type') == 'radio' || $(this).prop('type') == 'checkbox'){
            if($(this).is(':checked')){
                aaData[$(this).prop('name')] = $(this).val();
            }
        }else{
            if($(this).hasClass('html-code-textarea')){

                var cm_inst = $(this).data('CodeMirrorInstance');
                cm_inst.save();

            }
            aaData[$(this).prop('name')] = $(this).val();
        }
    });

    aaData.ajax = true;
    return aaData;
}


function fnPrepareAccordion(dialog){

    var acc = dialog.find(".accordion.use-menu-checkbox");
    if(acc.length){
        $(".chbList input[type='checkbox']").on('change', function(){
            var head = acc.find('.menu-group-' + this.value);
            var list = acc.find('.menu-group-list-' + this.value);
            if(head.length){
                head.toggle($(this).prop('checked'));
            }
            if(list.length && $(this).prop('checked') == false){
                list.toggle($(this).prop('checked'));
            }
        });
        var chlist = dialog.find(".menu-checkbox-list .chbList input[type='checkbox']");
        if(chlist.length){
            chlist.each(function(){
                var head = acc.find('.menu-group-' + $(this).val());
                if(head.length){
                    head.toggle($(this).prop('checked'));
                }
            });
        }
    }
}

/**
 * Przygotowywuje dilaog
 */
function fnPrepareDialog(dialog){
    fnDisappearingContent();
    dialog.find('.buttons').buttonset();
    dialog.find('select:not(.pqselect)').selectBoxIt();
    fnDatePicker(dialog);
    fnFixSelect('');

    // init tabs
    // add ajax if needed
    dialog.find(".tabs").tabs({

        beforeLoad: function( event, ui ) {
            /*
             var loader = $('<div class="small_loader"><img src="'+base+'images/loading_small.gif"></div>')
             .css('maring', '10px auto')
             .css('text-align', 'center');
             */


            var loader = $("<div>Loading...</div>");

            ui.panel.html(loader);

            ui.jqXHR.fail(function() {
                ui.panel.html("<div>An error occured. Couldn't load this tab.</div>");
            });

            ui.jqXHR.success(function() {
                ui.tab.data( "loaded", true );
            });

            ui.jqXHR.always(function() {
                // ...
            });
        },
        load: function (event, ui ) {
            var $panel = $(ui.panel);
            var html = $($panel.html());

            // replace

            html.find('input.disappearingContent').each(function() {
                $(this).bind("focus", function(){
                    if(this.value == this.defaultValue){
                        this.value='';
                        $(this).removeClass('grey');
                    }
                });
            });
            html.find('input.disappearingContent').each(function(){
                $(this).bind("blur", function(){
                    if(this.value==''){
                        this.value=this.defaultValue;
                        $(this).addClass('grey');
                    }
                });
            });

            fnDatePicker(html);
            html.find('.buttons').buttonset();
            html.find('select:not(.pqselect)').selectBoxIt();

            html.find(".accordion").accordion({
                heightStyle: "content",
                collapsible: true,
                active: false
            });

            html.find(".datetimepicker").datetimepicker({
                allowBlank: true
            });

            fnPrepareAccordion(html);

            html.find('.checkboxIt').ezMark();
            html.find('.tooltip').tooltipster();

            $panel.html(html);
        }
    });

    i = 0;
    dialog.find(".tabs .ui-tabs-nav li").each(function(){
        if($(this).hasClass('disabled')){
            dialog.find( ".tabs" ).tabs( "disable", i );
        }
        if($(this).hasClass('active')){
            dialog.find( ".tabs" ).tabs({ "active": i });
        }
        i++;
    });

    dialog.find(".accordion").accordion({
        heightStyle: "content",
        collapsible: true,
        active: false
    });

    $(".datetimepicker").datetimepicker({
        allowBlank: true
    });

    fnPrepareAccordion(dialog);

    dialog.find('.checkboxIt').ezMark();
    dialog.find('.dialogBtnSave').unbind("click");
    dialog.find('.tooltip').tooltipster();
    if( ! dialog.values.bPosition){
        dialog.values.bPosition = true;
        dialog.dialog('option', 'position', fnFindPosition());
    }

    if(dialog.find('.run-tests-console').length){

        setTimeout(function() {
            fnRunTests(dialog.find('.run-tests-console'));
        }, 200);
    }

}


function fnRunTests(console_box){

    var tests = console_box.find('.tests').val();
    var _console = console_box.find('.test-console');
    var loading = console_box.find('.console-loading');
    var tests_array = tests.split(',');

    var jqxhr = [];

    $.each( tests_array, function( key, value ) {

        var aaData = {};
        aaData.test = value;

        loading.show();

        jqxhr.push( $.ajax({
            type: "POST",
            url: base + 'superadmin/tests/execute',
            data: aaData,
            dataType: "html",
            cache: false,
            async: true
        }).done(function(sHtml) {

            // add response
            _console.append(sHtml);

            // add space
            _console.append('<span class="test-message">&nbsp;</span>');

            // scroll down
            _console.scrollTop(_console[0].scrollHeight);


        }).fail(function(e) {

            _console.append('<span class="test-message red">' + e + '</span>');

        }).always(function() {

            var hide = true;

            $.each( jqxhr, function( key, request ) {

                if(request.state() === 'pending')
                    hide = false;
            });

            if(hide === true){
                loading.hide();
            }
        }));

    });
}



/**
 * ustawia zawartość okna lub je zamyka
 * @param dialog obiekt okna
 */

function fnSetDialogContent(dialog){
    try {
        json = $.parseJSON(dialog.values.sData);
        fnCheckJsonLogin(json);
        if( fnJsonIfSuccess(dialog.values.sTableId, json) ){
            fnClosePopUp(dialog, true);
        }
        fnJsonIfError(json)

        if(aDataTables[dialog.values.sTableId] != null){
            aDataTables[dialog.values.sTableId].fnStandingRedraw();
        }
    }
    catch (e) {
        dialog.html(dialog.values.sData);
        if( dialog.find('.noError').size() != 1){
            $(dialog).parent().effect('shake',{ times:3 , distance: 10}, 700);
        }
        fnLoadDialog(dialog);
    }
}


/**
 * zamyka okno
 * może usunąć je z DOM
 * @param dialog obiekt okna
 * @param bRemove czy usunąć z DOM
 */
function fnClosePopUp(dialog, bRemove){

    $(dialog).parent().fadeOut(300 ,function(){
        $(dialog).dialog('close');
        if(bRemove){
            index = aDialogs.indexOf(dialog);
            if(index != -1){
                aDialogs.splice(index, 1);
            }
            $(dialog).dialog('destroy').remove();
        }
    });

    if($(".colpick")) $(".colpick").remove();
}

/**
 * Tworzy puste okno
 * @param sData dane do okna
 * @param dialog refernacja do obiektu okna
 * @param sSource ściezka do poliku z danymi
 * @param sTableId id tabeli do kórej tyczy się okno
 * @param sTitle tytuł okna
 * @param iWidth szerokość
 * @param iHeight wysokość
 * @param sMsg wiadomość w przypadku braku komunikacji
 * @param sModal czy okienko ma byc modalne
 * @return {Object}
 * @param sParent
 */
function fnCreateDialog(sData, dialog, sSource, sTableId, sTitle, iWidth, iHeight, sMsg, sParent, bModal, iZindex){

    bModal = typeof bModal !== 'undefined' ? bModal : false;
    iZindex = (typeof iZindex !== 'undefined')? iZindex : false;

    var tag = $('<div></div>');
    tag.addClass('dialog');
    tag.prop('title', sTitle);

    try {
        json = $.parseJSON(sData);
        if( json.login.status == false){
            window.location.reload();
            return null;
        }
    }catch(e){

    }

    tag.html($(sData));


    dialog = tag.dialog({
        width: iWidth,
        height: iHeight,
        modal: bModal,
        minHeight: '200',
        closeText: __lang('Zamknij'),
        hide: 'fade',
        resizable: false,
        collision: "none none",
        open: function(event, ui) {
            tag.tooltip({position:{
                my: "left bottom-10",
                at: "left top",
                collision: "none none"
            }});
        },
        close: function(ev, ui){fnClosePopUp(dialog, true)}
    });

    parent = null;

    if(sParent != null){
        if(aDialogs[sParent] != undefined){
            parent = aDialogs[sParent];
        }
    }

    fnFindPosition();

    dialog.values = {};
    dialog.values.sData = sData;
    dialog.values.sSource = sSource;
    dialog.values.sTableId = sTableId;
    dialog.values.sTitle  = sTitle;
    dialog.values.iWidth  = iWidth;
    dialog.values.iHeight  = iHeight;
    dialog.values.sMsg  = sMsg;
    dialog.values.parent = parent;
    dialog.values.sId = fnMakeId();
    dialog.values.bPosition = false;
    dialog.values.children = {};


    if(iZindex != false){

        var css = dialog.parent().attr('style');
        dialog.parent().attr('style', css + ' z-index: ' + iZindex + ';');
    }

    aDialogs.push(dialog);
    return dialog;
}

/**
 * czyta chceckoby z danej tabeli
 * @param sTableId tabela z której odczytane zostaną wartości
 * @return tablicę wartości
 */
function fnReadCheckboxes(sTableId){
    asId = [];
    $('#' + sTableId +' .checkboxId').each(function(){
        if($(this).prop('checked')){
            asId.push($(this).val());
        }
    });
    return asId;
}

/**
 * pokazuje loadera
 */
function fnShowLoader(){
    $('#loader').fadeIn(100);
}

/**
 * ukrywa ladera
 */
function fnHideLoader(){
    $('#loader').fadeOut(100);
}

/**
 * generuje unikalne id
 * @return {String}
 */
function fnMakeId(){
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for( var i=0; i < 10; i++ ){
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return Math.floor(Math.random() * 2^16);
}

/**
 * Klika btn zapisz kiedy wciśnie się enter w formularzu
 */
function fnSaveForm(event){
    event.preventDefault();
    $(event.target).find('.dialogBtnSave').click();
    return false;
}

/**
 * Znajduje pozycję dla nowego okna
 * @returns {Array}
 */
function fnFindPosition(){

    iX = iDefaultLeft;
    iY = iDefaultTop;
    $(aDialogs).each(function(){
        pos = $(this).dialog('option','position');
        x = pos[0];
        y = pos[1];

        if((x - iDefaultLeft) % 50 == 0 && (y - iDefaultTop) % 50 == 0 && (x - iDefaultLeft) / 50 == (y - iDefaultTop) / 50 ){
            iX = x;
            iY = y;
        }
    });
    iX+=50;
    iY+=50;
    return [iX, iY];
}

/**
 * odczytuje
 * @param sId
 * @param asValues
 * @returns {*}
 */
function fnReadInput(sId, asValues){
    tab = {};
    $(asValues).each(function(){
        tab[this] = [$(sId).val()];
    })
    return tab;
}

/**
 * Wszystko co trzeba zrobić po załądowaniu tabeli
 * @param sTableId
 */
function fnAfterTableLoad(sTableId){
    fnAddFilters(sTableId);
    fnToggleCheckboxes(sTableId);
    fnSwitchCheckboxes(sTableId);
    fnTooltip(sTableId)
}


function fnTooltipTable(sTableId){
    fnTooltip('#' + sTableId);
}

function fnTooltip(sSelector){
    $(sSelector + ' .tooltip').tooltipster();
}



/**
 *
 * @param input
 * @param multiplier
 * @returns {string}
 */
function str_repeat (input, multiplier) {
    var y = '';
    while (true) {
        if (multiplier & 1) {
            y += input;
        }
        multiplier >>= 1;
        if (multiplier) {
            input += input;
        }
        else {
            break;
        }
    }
    return y;
}

/**
 * Przełącza widoczność kolumny w danej tabeli
 * Sprawdza czy nie przekroczono limitu widoczności
 * @param sTableId      id tabeli
 * @param iLimit        limit widocznych tabel
 * @param iColumnNo     kolumna do zmiany
 * @param bVisible      zmiana na widoczną czy nie
 */
function fnSwitchColumn(event, sTableId, iColumnNo, iLimit , bVisible){
    oTable = aDataTables[sTableId];

    var iVisible = 0;
    $(oTable.fnSettings().aoColumns).each(function(){
        if(this.bVisible == true){
            iVisible+=1;
        }
    })

    if(iVisible < iLimit && bVisible == true){
        oTable.fnSetColumnVis( iColumnNo, bVisible );
    }else{
        if( ! bVisible){
            oTable.fnSetColumnVis( iColumnNo, bVisible );
            return 0;
        }
        $.jGrowl('Nie można wybrać więcej widocznych kolumn' , { group: 'alertInfo' });
        $(event.target).prop('checked', false);
        $(event.target).change();
    }
}
/**
 * Włącza edycje w td
 * @param event
 */
function fnEnableCell(sSelector){
    $(sSelector).find('.disabled').fadeOut(100, function(){
        $(sSelector).find('.enabled').fadeIn(100);
    });
    $(sSelector).find('input').keypress(function (e) {
        if (e.which == 13) {
            $(e.target).parent().find('.buttonSave').click();
        }
    });
}

/**
 * Włącza edycje w td
 * @param event
 */
function fnEnableCellCustom(sSelector){
    $(sSelector).find('.disabled').fadeOut(100, function(){
        $(sSelector).find('.enabled').fadeIn(100);
    });
    $(sSelector).find('input').keypress(function (e) {
        if (e.which == 13) {
            $(e.target).parent().find('.buttonSave').click();
        }
    });
    fnDimensionAutoComplete('.autoComplete', base+'assortment/products/autocompleteDimension');
}
/**
 * Wyłacza edycje komórki bez zapisania
 * @param sSelector
 */
function fnDisableCell(sSelector){
    $(sSelector).find('.enabled').fadeOut(100, function(){
        $(sSelector).find('.enabled input').each(function(){
            $(this).val($(this).prop("defaultValue"));
        })
        $(sSelector).find('input').removeClass('error');
        $(sSelector).find('.disabled').fadeIn(100);
    });
}
/**
 * Wyłacza edycje komórki bez zapisania i bez ustawiania defaultValue
 * @param sSelector
 */
function fnDisableCell2(sSelector){
    $(sSelector).find('.enabled').hide(10, function(){
        $(sSelector).find('input').removeClass('error');
        $(sSelector).find('.disabled').show(10);
    });
}


/**
 * Inicjuje datepickery wewnątrz biektów o podanym selektorze
 * @param sSelector
 */
function fnDatePicker(sSelector){

    $(sSelector).find('.datepicker').datepicker({

    });
}

/**
 * Zmienia edytowalność pol czułych na autoupdate. Przy blokadzie ustwia domyślną wartość pola;
 * @param sSelector     klasa pól które należy zablokować
 * @param bReadOnly     czy zablokować
 */
function fnReadonlyFields(sSelector, bReadOnly){
    if(bReadOnly){

        $(sSelector).each(function(){
            $(this).prop('readonly','readonly');
            $(this).val($(this).prop('defaultValue'));
        })
    }else{
        $(sSelector).prop('readonly',false);
    }
}

function fnGoTo(url){
    window.location = url;
}

function clearInput0(Selector) {
    $(Selector).val('');
    $(Selector).keyup('');
    $(Selector).blur('');
}


///////////////////////////////////////////////////////////////////////////////
/**
 * Uruchamia autocomplete zapisując atrybuty do i-tego inputa autoCompleteAttribute[i], gdzie i=0,...,attribute.length
 * @param sSelector
 * @param sSource
 */
function fnAutoCompleteAttributes(sSelector, sSource){ // autocomplete z dodatkowymi atrybutami
    // informacja o typie inputa: autocomplete, datepicker itp.
    $(sSelector).each(function() {
        var tag = $('<div></div>');//.addClass('infoIcon iconAutocomplete');
        tag.css({"float" : "left", "position" : "relative"});
        var ac = $(this).parent().find('.autoComplete').first();
        var aci = $(this).parent().find('.autoCompleteId').first();
        var acat = $(this).parent().find(".autoCompleteAttributes");
        var xnf = $(this).parent().find('.xNoFrame').first();
        ac.after(tag);
        tag.append(ac, aci, acat, xnf);
        tag = $('<div></div>').addClass('infoIcon iconAutocomplete');
        ac.after(tag);
    });


    $(sSelector).each(function(){
        $(this).keyup(function(){
            if($(this).val() == ''){
                $(this).parent().find('.autoCompleteId').val('');
                $(this).parent().find('.autoCompleteId').trigger('change');
            }
        })
        $(this).autocomplete({
            source: sSource,
            minLength: 1,
            select: function( event, ui ) {
                $(this).parent().find('.autoCompleteId').val(ui.item.id);
                for (var i = 0; i < ui.item.attributes.length; i++) {
                    $(this).parent().find('.autoCompleteAttribute'+i).val(ui.item.attributes[i]);
                }
                $(this).parent().find('.autoCompleteId').trigger('change');
            },
            change: function(event, ui) {
                if ( ! ui.item) {
                    $(this).val('');
                    $(this).parent().find('.autoCompleteId').val('');
                }
            }
        });
    });
}

/**
 * Sprawia, że po wybraniu elementu z listy autocompleta przepisywane są wszystkie wartości atrybutów
 * Zależności:
 * Dla każdego autoCompleteAttribute0, autoCompleteAttribute1 itd. musi istnieć odpowiednik w postaci autoCompleteAttributeDisplay0 itd.
 * @param {type} sSelector
 * @returns {undefined}
 */
function fnAutoCompleteAttributesDisplay(sSelector) {
    $(sSelector).find('.autoCompleteId').bind('change', function(){
        var i=0;
        $(sSelector).find('.autoCompleteAttributes').each(function() {
            $(this).parents().eq(1).find('.autoCompleteAttributeDisplay'+i++).val($(this).val());
        });
    });
}

/**
 * Pozwala ustawiać wartość inputa korzystając z ajaxowej podpowiedzi lub bez
 Zależności:
 * 3 inputy, jeden klasy .autoCompleteId - tu będzie ustawiane np. id wybranego pola z listy
 * drugi .autoCompleteText - tu będzie ustawiona wartość tego co wpisaliśmy z inputa
 * trzeci .autoComplete - tu będzie wpisywany tekst
 * Za każdym razem, w momencie gdy przestaniemy ustawiać inputa .autoComplete, value będzie miał ustawiony tylko jeden z dwóch pierwszych
 * @param sSelector
 * @param sSource
 */
function fnOptionalAutoComplete(sSelector, sSource){
    // informacja o typie inputa: autocomplete, datepicker itp.
    $(sSelector).each(function() {
        var tag = $('<div></div>');//.addClass('infoIcon iconAutocomplete');
        tag.css({"float" : "left", "position" : "relative"});
        var nameOfAutoComplete = sSelector.split(' ');
        nameOfAutoComplete = nameOfAutoComplete[nameOfAutoComplete.length-1];
        var ac = $(this).parent().find(nameOfAutoComplete).first();
        var aci = $(this).parent().find('.autoCompleteId').first();
        var xnf = $(this).parent().find('.xNoFrame').first();
        var act = $(this).parent().find('.autoCompleteText').first();
        ac.after(tag);
        tag.append(ac, aci, xnf, act);
        tag = $('<div></div>').addClass('infoIcon iconAutocomplete');
        ac.after(tag);
    });



    $(sSelector).each(function(){
        $(this).keyup(function(){
            if($(this).val() == ''){
                $(this).parent().find('.autoCompleteId').val('');
                $(this).parent().find('.autoCompleteId').trigger('change');
            }
        })
        $(this).autocomplete({
            source: sSource,
            minLength: 1,
            select: function( event, ui ) {
                $(this).parent().find('.autoCompleteId').val(ui.item.id);
                $(this).parent().find('.autoCompleteText').val('');
                $(this).parent().find('.autoCompleteId').trigger('change');



            },
            change: function(event, ui) {
                if ( ! ui.item) {
                    $(this).parent().find('.autoCompleteId').val('');
                    $(this).parent().find('.autoCompleteText').val($(this).val());
                }
            }
        });
    });
}

function fnStageFieldsAutoComplete(sSelector, sSource){

    // informacja o typie inputa: autocomplete, datepicker itp.
    $(sSelector).each(function() {
        var tag = $('<div></div>');//.addClass('infoIcon iconAutocomplete');
        tag.css({"float" : "left", "position" : "relative"});
        var nameOfAutoComplete = sSelector.split(' ');
        nameOfAutoComplete = nameOfAutoComplete[nameOfAutoComplete.length-1];
        var ac = $(this).parent().find(nameOfAutoComplete).first();

        var aci = $(this).parent().find('.autoCompleteId').first();
        var xnf = $(this).parent().find('.xNoFrame').first();
        var act = $(this).parent().find('.autoCompleteText').first();
        ac.after(tag);
        tag.append(ac, aci, xnf, act);
        tag = $('<div></div>').addClass('infoIcon iconAutocomplete');
        ac.after(tag);
    });



    $(sSelector).each(function(){

        $(this).focus(function(){

        });
/*
        $(this).keyup(function(){
            console.log('now');

            if($(this).val() == ''){
                $(this).parent().find('.autoCompleteId').val('');
                $(this).parent().find('.autoCompleteId').trigger('change');
            }
        });
*/
        var closing = false;
        $(this).autocomplete({
            source: sSource + $(this).attr('id'),
            minLength: 0,
            close: function()
            {
                // avoid double-pop-up issue
                closing = true;
                setTimeout(function() { closing = false; }, 300);
            }
        })
            .focus(function() {
                if (!closing)
                    $(this).autocomplete("search");
            });
        });

}

function fnCustomerAutoComplete(sSelector, sSource) {
    // informacja o typie inputa: autocomplete, datepicker itp.
    $(sSelector).each(function () {
        var tag = $('<div></div>');//.addClass('infoIcon iconAutocomplete');
        tag.css({"float": "left", "position": "relative"});
        var nameOfAutoComplete = sSelector.split(' ');
        nameOfAutoComplete = nameOfAutoComplete[nameOfAutoComplete.length - 1];
        var ac = $(this).parent().find(nameOfAutoComplete).first();
        var aci = $(this).parent().find('.autoCompleteId').first();
        var xnf = $(this).parent().find('.xNoFrame').first();
        var act = $(this).parent().find('.autoCompleteText').first();
        ac.after(tag);
        tag.append(ac, aci, xnf, act);
        tag = $('<div></div>').addClass('infoIcon iconAutocomplete');
        ac.after(tag);
    });
    $(sSelector).each(function () {
        $(this).keyup(function () {
            if ($(this).val() == '') {
                $(this).parent().find('.autoCompleteId').val('');
                $(this).parent().find('.autoCompleteId').trigger('change');
            }
        })
        $(this).autocomplete({
            source: sSource,
            minLength: 1,
            select: function (event, ui) {

                $.each(ui.item.data, function (key, value) {

                        var item = $(document).find('#'+key);
                        if(item.prop("tagName") == 'SELECT'){
                            item.find('option[value=\''+value+'\']').prop('selected', true);
                            if( item.find('country') ){
                                if(this == "United States"){
                                    $(document).find('.order-billing-region').addClass('hide');
                                    $(document).find('.order-billing-state').removeClass('hide');
                                }
                                else{
                                    $(document).find('.order-billing-region').removeClass('hide');
                                    $(document).find('.order-billing-state').addClass('hide');
                                }}
                            if( item.find('scountry') ){
                                if(this == "United States"){
                                    $(document).find('.order-shipping-region').addClass('hide');
                                    $(document).find('.order-shipping-state').removeClass('hide');
                                }
                                else{
                                    $(document).find('.order-shipping-region').removeClass('hide');
                                    $(document).find('.order-shipping-state').addClass('hide');
                                }}



                            var selectBoxSelectBoxIt = item.data('selectBoxSelectBoxIt');
                            if(selectBoxSelectBoxIt !== undefined) {
                                selectBoxSelectBoxIt.refresh();
                            }else{
                                console.log('could not read data property')
                            }

                        }else{
                            item.val(value);
                        }

                        var val = 0;
                        $('input:checkbox[value="' + val + '"]').attr('checked', false);
                        var val = 1;
                        $('input:checkbox[value="' + val + '"]').attr('checked', true);
                    }
                );      if($('#default_send').attr('checked')) {
                    $(".different").removeClass('disabled-form');

                } else {
                    $(".different").addClass('disabled-form');


                }
            },
            change: function (event, ui) {
                $.each(ui.item.data, function (key, value) {
                        $(document).find('#'+key).val(value);
                    }


                );


            }
        });
    });
}


function fnDimensionAutoComplete(sSelector, sSource) {
    // informacja o typie inputa: autocomplete, datepicker itp.
    $(sSelector).each(function () {
        var tag = $('<div></div>');//.addClass('infoIcon iconAutocomplete');
        tag.css({"float": "left", "position": "relative"});
        var nameOfAutoComplete = sSelector.split(' ');
        nameOfAutoComplete = nameOfAutoComplete[nameOfAutoComplete.length - 1];
        var ac = $(this).parent().find(nameOfAutoComplete).first();
        var aci = $(this).parent().find('.autoCompleteId').first();
        var xnf = $(this).parent().find('.xNoFrame').first();
        var act = $(this).parent().find('.autoCompleteText').first();
        ac.after(tag);
        tag.append(ac, aci, xnf, act);
        tag = $('<div></div>').addClass('infoIcon iconAutocomplete');
        ac.after(tag);
    });
    $(sSelector).each(function () {
        $(this).keyup(function () {
            if ($(this).val() == '') {
                $(this).parent().find('.autoCompleteId').val('');
                $(this).parent().find('.autoCompleteId').trigger('change');
            }
        })
        $(this).autocomplete({
            source: sSource,
            minLength: 1,
            select: function (event, ui) {
                $.each(ui.item.data, function (key, value) {
                        var item = $(document).find('#'+key);
                        if(item.prop("tagName") == 'SELECT'){
                            item.find('option[value=\''+data+'\']').prop('selected', true);


                            var selectBoxSelectBoxIt = item.data('selectBoxSelectBoxIt');
                            if(selectBoxSelectBoxIt !== undefined) {
                                selectBoxSelectBoxIt.refresh();
                            }else{
                                console.log('could not read data property')
                            }

                        }else{
                            item.val(value);
                        }


                    }
                );
            },
            change: function(event, ui) {
                var autocomplete = document.getElementById('code_'+data.sps_id);
                autocomplete.value = $(this).val();


            }



        });
    });
}
/**
 * Wykonuje proste akcje, ale w odroznieniu od fnSimpleActionConfirm ustalamy tekst wyświetlany na przycisku potwierdzenia: sConfirm

 * @param asId          Id rekordu
 *                      null - dla akcji nie wymagającej podania id
 *                      [] - dla akcji która ma pobrać id z checkboxów w tabeli
 *                      [1, 2, 3] - dla akcji o id zdefiniowanych w wywołaniu
 * @param sTableId      Określenie zbioru danych
 * @param sSource       ściezka do pliku
 * @param sMsg          Nazwa akcji "user friendly". Uzywana w przypadku błędu. "Nie udało sie wykonać sMsg"
 * @param sTitle        Tytuł okna zapytania
 * @param sQuestion     Tekst okna zapytania
 * @param sConfirm      Tekst potwierdzenia
 */
function fnSimpleActionConfirm2(sSource, asId, sTableId, sMsg, sTitle, sQuestion, sConfirm){
    var aaData;
    aaData = fnSimpleActionCollectData(sTableId, asId);
    if(aaData != 0){
        fnPrepareConfirmDialog(sTitle, sQuestion);

        var dialogConfirm;
        dialogConfirm = $("#dialogConfirm").dialog({
            open: function (event, ui) {
                $('#dialogConfirmBtnOk').html('<span class="icon ok"></span><span class="name">'+sConfirm+'</span>');
                $('#dialogConfirmBtnOk').click(function () {
                    fnShowLoader();
                    aaData.ajax = true;
                    oResponse = $.post(sSource, aaData, function (json) {                            // przesyłamy
                            fnHideLoader();
                            fnCheckJsonLogin(json);
                            fnJsonIfSuccess(sTableId, json);
                            fnJsonIfError(json);
                            aDataTables[sTableId].fnStandingRedraw(null);

                            try{
                                if(typeof json !== 'undefined' && json != null){
                                    if(typeof json.callback !== 'undefined' && typeof json.callback.method !== 'undefined'){
                                        fnCallback(json.callback);
                                    }
                                }
                            }catch(e){
                                console.log(e);
                            }

                        }, "json"
                    );
                    oResponse.error(function () {
                        fnConnectionError(sTableId,sMsg);
                    });
                    fnClosePopUp(dialogConfirm, false)
                });
                $('#dialogConfirmBtnCancel').click(function () {
                    fnClosePopUp(dialogConfirm, false)
                });
            },
            resizable: false,
            modal: true,
            minHeight: '100px',
            closeOnEscape: true
        });
    }
}


function fnSimpleActionConfirmRemoveTask(sSource, asId, sTableId, sMsg, sTitle, sQuestion, sConfirm){
    var aaData;
    aaData = fnSimpleActionCollectData(sTableId, asId);
    if(aaData != 0){
        fnPrepareConfirmDialog(sTitle, sQuestion);

        var dialogConfirm;
        dialogConfirm = $("#dialogConfirm").dialog({
            open: function (event, ui) {
                $('#dialogConfirmBtnOk').html('<span class="icon ok"></span><span class="name">'+sConfirm+'</span>');
                $('#dialogConfirmBtnOk').click(function () {
                    fnShowLoader();
                    aaData.ajax = true;
                    oResponse = $.post(sSource, aaData, function (json) {                            // przesyłamy
                            fnHideLoader();
                            fnCheckJsonLogin(json);
                            fnJsonIfSuccess(sTableId, json);
                            fnJsonIfError(json);


                            try{
                                if(typeof json !== 'undefined' && json != null){
                                    if(typeof json.callback !== 'undefined' && typeof json.callback.method !== 'undefined'){
                                        fnCallback(json.callback);
                                    }
                                }
                            }catch(e){
                                console.log(e);
                            }

                        }, "json"
                    );
                    oResponse.error(function () {
                        fnConnectionError(sTableId,sMsg);
                    });
                    fnClosePopUp(dialogConfirm, false)
                });
                $('#dialogConfirmBtnCancel').click(function () {
                    fnClosePopUp(dialogConfirm, false)
                });
            },
            resizable: false,
            modal: true,
            minHeight: '100px',
            closeOnEscape: true
        });
    }
}
//-------------------- grupowanie -----------------------

/**
 * Randomizer
 * @returns {undefined}
 */
function randomizeGroup(a, b) {
    if(Math.random()>0.5)
        return $(a).find('input').first().val()-$(b).find('input').first().val();
    else
        return $(b).find('input').first().val()-$(a).find('input').first().val();
}
/**
 * Descending comparator for ao_group_id
 */
function descGroup(markerColumn) {
    return function(a, b){return $(b).find('td:nth-child('+markerColumn+') input').first().val().localeCompare($(a).find('td:nth-child('+markerColumn+') input').first().val(), 'kn', {numeric : "true"});}
}

/**
 * Ascending comparator for ao_group_id - tylko na podstawie pierwszej komórki w wierszu
 */
function ascGroup(markerColumn) {
    return function(a, b){return $(a).find('td:nth-child('+markerColumn+') input').first().val().localeCompare($(b).find('td:nth-child('+markerColumn+') input').first().val(), 'kn', {numeric : "true"});}
    //return x-y; // nie działa gdy porównujemy stringi
}

/**
 * Sortuje jQuery array na podstawie fComparator
 * @param {jQuery Array} jqArray
 * @param {function} fComparator
 * @returns {unresolved}
 */
function fnSort(jqArray, fComparator) {
    return $(jqArray.get().sort(fComparator));
}

/**
 * Sortuje WYŚWIETLANĄ tabelę (bez udziału serwera)
 * Zależności:
 * Jeżeli tabela jest pusta to posiada tylko jedną komórkę w tbody - dataTables_empty

 * @returns -1 jeżeli tabela jest pusta

 */
function fnSortDataTable(sTableId, fComparator) {
    var dt_tbody = $(sTableId + ' tbody tr');
    if(($(dt_tbody).find('td').attr("class")==="dataTables_empty")||(dt_tbody.length===0)) {  // nie sortujemy pustej tabelki
        return -1;
    } else {
        dt_tbody = fnSort(dt_tbody, fComparator);
        $(sTableId + ' tbody').append(dt_tbody);
    }
}
/**
 * Grupuje wiersze na podstawie n-tej kolumny (począwszy od nr1), która musi zawierać input z id np. grupy_ceny_itd.
 * Nie wymagane jest wstępne posortowanie wierszy.
 * Zależności:
 * Tabela zawiera kolumnę o nr markerColumn, której pierwszy input zawiera informację na podstawie której wiersze będą scalane
 * Uwaga:
 * Sortowanie nastepuje tylko przy pierwszym wywołaniu fnGroup, dlatego trzeba zadbać o prawidłowy input znacznikowy
 * @param {string} sTableId - identyfikator tabeli np. "#allegroOrders"
 * @param {int} markerColumn - nr kolumny, która zawiera informacje na podstawie której wiersze będą scalane
 * @param {array} aGroupColumns - numery kolumn (bezwględne, tzn. te które są widoczne, poczynając od 1), których komórki będą scalane
 * @param {function} fnComparator - komparator do sortowania (ascGroup, descGroup)
 * @param {boolean} bSeparator - czy odzielać wiersze w grupie przerywaną linią?
 * @returns {undefined}
 */

function fnGroup(sTableId, markerColumn, aGroupColumns, fnComparator, bSeparator) {
    // sprawdz czy tabela nie byla grupowana
    if(!$(sTableId).hasClass('fnGrouped')) {
        $(sTableId).addClass('fnGrouped');
        if(fnSortDataTable(sTableId, fnComparator(markerColumn))===-1) {  //  pusta tabelka
            return;
        }  // koniecznie trzeba wcześniej posortować!!!
    }
    $(sTableId + ' tbody tr').attr('class', 'singleTr'); // wyczyść klasy (kolorowanie odd itp.)

    var firstTds = $(sTableId + ' tbody tr td:nth-child('+markerColumn+')'); // tablica komórek z markerem z każdego wiersza tbody // na podstawie firstTds odbywa się scalanie (po id z jego inputa)

    var aGroupColumnsTds = []; // komórki do scalenia
    for(var c=0; c<aGroupColumns.length; c++) {
        aGroupColumnsTds[c]=$(sTableId + ' tbody tr td:nth-child('+aGroupColumns[c]+')');
    }

    for(var i=0; i<firstTds.length;) {
        var tempGroupId = $(firstTds[i]).find('input').first().val();
        var tempId = tempGroupId;
        var rowspan=1;
        var firstInd=i;
        for(var j=i+1; j <= firstTds.length && tempGroupId === tempId; j++, i++) {
            tempId = $(firstTds[j]).find('input').first().val();
            if(tempGroupId === tempId) {     // znaleziono wiersz o tym samym id grupy
                /*ustawiamy hovera dla pierwszego wiersza, który jest częścią grupy (mainRow)*/
                var mainRow = $(firstTds[firstInd]).parent();  // wszystkie tr-y głównych wierszy

                // ustawiamy kreskę odzielającą zgrupowane wiersze dla pierwszego wiersza
                if(bSeparator) {
                    var k=1;
                    mainRow.find('td').each(function(){
                        if(aGroupColumns.indexOf(k++)===-1) // scalone komórki omijamy
                            $(this).css('border-bottom', 'dashed 1px #ccc');
                    });
                }
                ////

                mainRow.attr("class", "groupedTr"+tempGroupId);
                mainRow.mouseover(function(){
                    //var primaryClass = $(this).attr("class");
                    //$("."+primaryClass).attr("class", primaryClass + " groupedTr-focus noHover");
                    $("."+$(this).attr("class")).addClass("groupedTr-focus");
                });
                mainRow.mouseleave(function(){
                    var select=$(this).attr("class").replace(" ", ".");
                    $("."+select).removeClass("groupedTr-focus");
                });

// to samo dla podwierszy

                /*ustawiamy hovera dla zgrupowanych wierszy*/
                $(firstTds[j]).parent().attr("class", "groupedTr"+tempGroupId); // najpierw odpowiednio oznaczamy
                var subRow=$(firstTds[j]).parent();

                // ustawiamy kreskę odzielającą zgrupowane wiersze
                if(bSeparator) {
                    k=1;
                    subRow.find('td').each(function(){
                        if(aGroupColumns.indexOf(k++)===-1) {// scalone komórki omijamy
                            $(this).css('border-bottom', 'dashed 1px #ccc');
                        }
                    });
                }
                ////

                $(firstTds[j]).parent().mouseover(function(){
                    $("."+$(this).attr("class")).addClass("groupedTr-focus");
                });
                $(firstTds[j]).parent().mouseleave(function(){
                    var select=$(this).attr("class").replace(" ", ".");
                    $("."+select).removeClass("groupedTr-focus");
                });


                for(var c=0; c<aGroupColumns.length; c++) {
                    if(aGroupColumns[c] === $(aGroupColumnsTds[c][j]).parent().children().length+1) {  // jeżeli usuwamy komórkę z ostatniej kolumny to trzeba naprawić border
                        $(aGroupColumnsTds[c][j]).prev().css("border-right-width", "0px");
                    }
                    $(aGroupColumnsTds[c][j]).css("display", "none");  // usuń wszystkie niepotrzebne komórki z tego wiersza
                    $(aGroupColumnsTds[c][j]).addClass("groupedSubRowTd");
                }
                rowspan++;
            } else {
                if($(firstTds[j-1]).hasClass("groupedSubRowTd")){   // operacje na ostatnich subRows
                    $(firstTds[j-1]).parent().find('td').css('border-bottom', '1px solid #dddddd');
                }
            }
        }



        for(var c=0; c<aGroupColumns.length; c++) {
            $(aGroupColumnsTds[c][firstInd]).attr("rowspan", rowspan);
        }
    }

}

/**
 * Zmienia na standard akceptowany przez FF i CHROME (php: DATE_ATOM).
 * Przykład:
 * 2013-09-27 15:03:53 zamieni na 2013-09-27T15:03:53+02:00
 * Aby zamienić na czas w ms korzystamy z funkcji Date.parse(data);
 * @param {type} sPrimaryTime
 * @returns {String} data
 */
function normalizeDate(sPrimaryTime) {
    var sep = sPrimaryTime.indexOf(" ");
    var prefix = sPrimaryTime.substr(0, sep)
    var sufix = sPrimaryTime.substr(sep+1, sPrimaryTime.length);
    return prefix+'T'+sufix+"+02:00";
}

function fnHideRows(except) {
    if(typeof except !== "undefined")
        var rows = $(except).closest('tbody');
    else
        var rows = $('tbody');
    rows.find('tr').each(function () {
        if(!$(this).find('input').last().prop('checked')) {
            $(this).css("opacity", "0.5");
        } else {
            $(this).css("opacity", "1");
        }
    });
}

/*
 *  dodaje ikone do inputa np. kalendarz, kod kreskowy
 *  Przykład: fnAddIcon('.datePicker', 'iconDatepicker');
 */
function fnAddIcon(sSelector, sClass) {
    var tag = $('<div></div>');
    tag.css({"float" : "left", "position" : "relative"});
    var ac = $(sSelector);
    ac.after(tag);
    tag.append(ac);
    tag = $('<div></div>').addClass('infoIcon '+sClass);
    ac.after(tag);
}

function fnAddText(sSelector, sText) {
    $(sSelector).each(function() {
        var tag = $('<div></div>');
        tag.css({"float" : "left", "position" : "relative"});
        var ac = $(this);
        ac.after(tag);
        tag.append(ac);
        tag = $('<div></div>').text(sText).addClass('infoText');
        ac.after(tag);
        var style = ac.attr('style')+'padding-right: 14px !important';
        ac.attr('style', style);
    });
}

function fnClearTbody(sTableId) {
    $(sTableId).find('tbody tr').remove();
}

function dirname (path) {
    // http://kevin.vanzonneveld.net
    return path.replace(/\\/g, '/').replace(/\/[^\/]*\/?$/, '');
}

function basename (path, suffix) {
    // http://kevin.vanzonneveld.net
    // *     example 1: basename('/www/site/home.htm', '.htm');
    // *     returns 1: 'home'
    // *     example 2: basename('ecra.php?p=1');
    // *     returns 2: 'ecra.php?p=1'
    var b = path.replace(/^.*[\/\\]/g, '');

    if (typeof suffix === 'string' && b.substr(b.length - suffix.length) == suffix) {
        b = b.substr(0, b.length - suffix.length);
    }

    return b;
}
function pathinfo (path, options) {
    // http://kevin.vanzonneveld.net
    // *     example 1: pathinfo('/www/htdocs/index.html', 'PATHINFO_BASENAME');
    // *     returns 1: 'index.html'
    // *     example 2: pathinfo('/www/htdocs/index.html');
    // *     returns 2: {dirname: '/www/htdocs', basename: 'index.html', extension: 'html', filename: 'index'}
    // Working vars
    var opt = '',
        optName = '',
        optTemp = 0,
        tmp_arr = {},
        cnt = 0,
        i = 0;
    var have_basename = false,
        have_extension = false,
        have_filename = false;

    // Input defaulting & sanitation
    if (!path) {
        return false;
    }
    if (!options) {
        options = 'PATHINFO_ALL';
    }

    // Initialize binary arguments. Both the string & integer (constant) input is
    // allowed
    var OPTS = {
        'PATHINFO_DIRNAME': 1,
        'PATHINFO_BASENAME': 2,
        'PATHINFO_EXTENSION': 4,
        'PATHINFO_FILENAME': 8,
        'PATHINFO_ALL': 0
    };
    // PATHINFO_ALL sums up all previously defined PATHINFOs (could just pre-calculate)
    for (optName in OPTS) {
        OPTS.PATHINFO_ALL = OPTS.PATHINFO_ALL | OPTS[optName];
    }
    if (typeof options !== 'number') { // Allow for a single string or an array of string flags
        options = [].concat(options);
        for (i = 0; i < options.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[options[i]]) {
                optTemp = optTemp | OPTS[options[i]];
            }
        }
        options = optTemp;
    }

    // Internal Functions
    var __getExt = function (path) {
        var str = path + '';
        var dotP = str.lastIndexOf('.') + 1;
        return !dotP ? false : dotP !== str.length ? str.substr(dotP) : '';
    };


    // Gather path infos
    if (options & OPTS.PATHINFO_DIRNAME) {
        var dirname = this.dirname(path);
        tmp_arr.dirname = dirname === path ? '.' : dirname;
    }

    if (options & OPTS.PATHINFO_BASENAME) {
        if (false === have_basename) {
            have_basename = this.basename(path);
        }
        tmp_arr.basename = have_basename;
    }

    if (options & OPTS.PATHINFO_EXTENSION) {
        if (false === have_basename) {
            have_basename = this.basename(path);
        }
        if (false === have_extension) {
            have_extension = __getExt(have_basename);
        }
        if (false !== have_extension) {
            tmp_arr.extension = have_extension;
        }
    }

    if (options & OPTS.PATHINFO_FILENAME) {
        if (false === have_basename) {
            have_basename = this.basename(path);
        }
        if (false === have_extension) {
            have_extension = __getExt(have_basename);
        }
        if (false === have_filename) {
            have_filename = have_basename.slice(0, have_basename.length - (have_extension ? have_extension.length + 1 : have_extension === false ? 0 : 1));
        }

        tmp_arr.filename = have_filename;
    }


    // If array contains only 1 element: return string
    cnt = 0;
    for (opt in tmp_arr) {
        cnt++;
    }
    if (cnt == 1) {
        return tmp_arr[opt];
    }

    // Return full-blown array
    return tmp_arr;
}

function fnDisableRows(sSelector, marker) {
    $(sSelector).each(function(){
        if($(this).val() == marker) {
            bl_row = $(this).closest('tr');
            bl_row.css("opacity", "0.5");
            bl_row.find('td').css("background", "#A0A0A0");
            bl_row.find("input").each(function(){$(this).prop("disabled", "disabled")});
            bl_row.find("div").each(function(){$(this).prop("onclick", "")});
        }
    });
}

/**
 * Blokuje pola typu input/select/textarea/div wewnątrz danego selektora.
 * Usuwane są: zdarzenia click i property onclick dla kazdego diva
 * @param {type} sSelector
 * @param {type} opacity - jeżeli ustawimy, to blokowane elementy będą przezroczyste
 * @returns {undefined}
 */
function fnDisable(sSelector, opacity) {
    if(typeof opacity === "undefined")
        var opacity = "1";
    $(sSelector).each(function(){
        $(sSelector).find("input").each(function(){$(this).prop("disabled", "disabled").css("opacity", opacity);});
        $(sSelector).find("select").each(function(){$(this).prop("disabled", "disabled").css("opacity", opacity);});
        $(sSelector).find("textarea").each(function(){$(this).prop("disabled", "disabled").css("opacity", opacity);});
        $(sSelector).find("div").each(function(){$(this).prop("onclick", "").css("opacity", opacity);});
        $(sSelector).find("div").each(function(){$(this).unbind("click").css("opacity", opacity);});
        $(sSelector).find("label").each(function(){$(this).unbind("click").css("opacity", opacity);});
    });
    $('.dialogBtnSave').unbind("click");
}

/**
 * Wykonuje funkcje fAction na elementach input/select/textarea/div.button wewnątrz danego selektora.
 * @param {type} sSelector
 * @param {type} fAction
 * @returns {undefined}
 */
function fnActionOnInteractiveElements(sSelector, fAction) {
    $(sSelector).each(function(){
        $(sSelector).find("input").each(function(){fAction(this);});
        $(sSelector).find("select").each(function(){fAction(this);});
        $(sSelector).find("textarea").each(function(){fAction(this);});
        $(sSelector).find("div.button").each(function(){fAction(this);});
        $(sSelector).find("label").each(function(){fAction(this);});

        $(sSelector).find(".selectboxit-container").each(function(){fAction(this);});
        $(sSelector).find(".has-pretty-child").each(function(){fAction(this);});
    });
}

/**
 * //.has-pretty-child, .selectboxit-container
 * Odblokowuje pola typu input/select/textarea/div wewnątrz danego selektora.
 * @param {type} sSelector
 * @returns {undefined}
 */
function fnEnable(sSelector) {
    $(sSelector).each(function(){
        $(sSelector).each(function(){$(this).prop("disabled", "").css("opacity", 1);});
    });
}

/**
 * Ajaxowo odświeża wybrany blok kodu np. table, div, itp.
 * Zależności:
 * @param {string} sSelector - div który odświeżamy
 * @param {string} sSource   - adres akcji, która przesyła widok
 * @param {object} oParams   - obiekt, który przesyłamy ajaxem
 * @param {function} fnAfterLoad   - funkcja wykonywana po odebraniu danych
 * @returns {unresolved} */
function fnRefresh(sSelector, sSource, oParams, fnAfterLoad){
    fnShowLoader();
    oParams.ajax = true;

    $.ajax({
        url: sSource,
        data: oParams,
        type: 'POST',
        success: function(sData){
            fnHideLoader();
            $(sSelector).html(sData);              // nadpisuje wcześniejszy blok
            $(sSelector).find('select').selectBoxIt();
            $(sSelector).find('input.pretty').prettyCheckable({
                color: 'red'
            });
            fnAfterLoad();
        }
    });
    return;
}

/**
 * Funkcja do prostej komunikacji z serwerem za pomocą ajaxa
 * @param {string} sSource - adres akcji z którą się komunikujemy
 * @param {object} oParams - obiekt przesyłany ajaxem
 * @param {function} _fSuccess (=function(json){}) - funkcja wywoływana po odebraniu danych od serwera
 * @param {boolean} _bShowLoader (=false) - czy pokazywać pasek ładowania
 * @param {boolean} _bAsync (=true) - czy komunikacja asynchroniczna
 * @returns {unresolved}
 */
function fnAjax(sSource, oParams, _fSuccess, _bShowLoader, _bAsync){
    // obsługa argumentów domyslnych
    if(typeof _fSuccess !== "undefined")
        var fSuccess = _fSuccess;
    else
        var fSuccess = function(){};
    if(typeof _bShowLoader !== "undefined")
        var bShowLoader = _bShowLoader;
    else
        var bShowLoader = false;
    if(typeof _bAsync !== "undefined")
        var bAsync = _bAsync;
    else
        var bAsync = true;

    if(bShowLoader)
        fnShowLoader();
    var aaData={};
    aaData = oParams;
    aaData.ajax = true;

    $.ajax({
        url: sSource,
        data: aaData,
        type: 'POST',
        async: bAsync,
        success: function(json){
            if(bShowLoader) {
                fnHideLoader();
            }
            fnCheckJsonLogin(json);
            try {
                json = $.parseJSON(json);
            } catch(e) {
                $.jGrowl( "Nie udało się sparsować odpowiedzi serwera", { group: 'alertError' });
                return;
            }

            if(json.hasOwnProperty('ok') && json.ok.hasOwnProperty('status') && json.ok.status === true) {
                $.jGrowl( json.ok.message, { group: 'alertOk' });
            }
            if(json.hasOwnProperty('error') && json.error.hasOwnProperty('status') && json.error.status === true) {
                $.jGrowl( json.error.message, { group: 'alertError' });
            }
            fSuccess(json);
        }
    });
    return;
}

/**
 * Chroni przed domyślną akcją po zdarzeniu submit
 */
function fnDoNothingForm(event){
    event.preventDefault();
    return false;
}

/**
 * Koloruje wiersze na podstawie markera (również te pogrupowane)
 * @param {type} sTableId - id tabelki
 * @param {type} sMarker - klasa, na podstawie której będzie wyciągana informacja o
 * @returns {undefined}
 */
function fnColorRows(sTableId, sMarker, sColor){
    var rowsSelector = sTableId+' tbody tr';
    $(rowsSelector).each(function(){
        var cl = $(this).attr("class");
        if(cl.indexOf("groupedTr")==0){
            if(typeof $(this).find(sMarker)[0]!=='undefined') {
                $('.'+cl).css("background-color", sColor);
            }
        } else {
            if(typeof $(this).find(sMarker)[0]!=='undefined') {
                $(this).attr("class", "").css("background-color", sColor);
            }
        }
    });
}

/**
 * Funkcja zliczająca obiekty sSelector na podstawie atrybutu sAttr.
 * Zwraca obiekt {atrybut: ilośc wystąpień}
 * @param {jQuery Array} jqArray tablica jQuery (przykład: $('tbody').first().find('tr'))
 * @param {string} sAttr
 * @returns {object} obiekt
 */
function fnCount(jqArray, sAttr) {
    var unique = {};
    $(jqArray).each(function(){
        var cl = $(this).attr(sAttr);
        if(!unique.hasOwnProperty(cl)) {
            unique[cl] = 1;
        } else {
            unique[cl]++;
        }
    });
    return unique;
}

function fnMakeOnlyNumeric(sSelector) {
    $(sSelector).keyup(function(){  //unbind('keyup')
        var primary=$(this).val();

        var start = this.selectionStart,
            end = this.selectionEnd;

        $(this).val( primary.replace(/[^0-9,.]/g, '') );

        // restore from variables...
        this.setSelectionRange(start, end);
    });
}

var gallery_w, gallery_h;
function setImgSize(imgSrc, f) {
    var newImg = new Image();

    newImg.onload = function() {
        gallery_w = newImg.height;
        gallery_h = newImg.width;
        f();
    }

    newImg.src = imgSrc; // this must be done AFTER setting onload
}


/**
 * pobieranie tlumaczen do bazy danych

 */
function fnGetTranslates(lang,type){
    fnShowLoader();
    var aaData={};
    aaData.ajax = true;
    aaData.type = type;
    aaData.lang = lang;

    oResponse = $.post(base+'settings/translations/getKeysFromFiles', aaData, function (json) {                            // przesyłamy

            $.jGrowl( 'Frazy zostały pobrane.', { group: 'alertOk' });
            aDataTables['settingsTranslates'].fnStandingRedraw(null);
            fnHideLoader();

        }, "json"
    ).error(function(){
            $.jGrowl("Wystąpił błąd podczas pobierania danych" , { group: 'alertError' });
            fnHideLoader();
        });

}

/**
 * generowanie pliku jezykowego

 */
function fnGenereteNewTranslateFile(lang, type){
    fnShowLoader();
    var aaData={};
    aaData.ajax = true;
    aaData.type = type;
    aaData.lang = lang;

    oResponse = $.post(base+'settings/translations/generateFile', aaData, function (json) {                            // przesyłamy

            $.jGrowl( 'Plik został wygenerowany.', { group: 'alertOk' });
            fnHideLoader();

        }, "json"
    ).error(function(){
            $.jGrowl("Wystąpił błąd podczas generowania pliku" , { group: 'alertError' });
            fnHideLoader();
        });
}


/**
 *
 * @param {type} obj
 * @returns {undefined}
 */
function fnPopupInsertImage(obj, data){

    if(typeof data != 'undefined'){

        // try to find input field
        var ckeinput = $(document).find('.cke_dialog')
            .find('input.cke_dialog_ui_input_text')
            .filter(':first');

        // close this popup
        fnClosePopUp($(obj).closest('.dialog'), false);

        ckeinput.focus().val(data).change();
        ckeinput.trigger("change");
    }
}


/**
 * Storing checkbox selecion in datatables
 * @param {this} obj
 * @param {string} sDatatable
 * @returns {undefined}
 */
function fnCheckboxItStore(obj, sTableId){

    var store = $("#" + sTableId).closest(".popupAdd").find(".checkBoxIt-store");

    if(store.length > 0){

        var $obj = $(obj);
        var val = $obj.val();

        if($obj.prop( "checked" )){

            store.append( $('<input type="hidden" id="chbix-store-'+ val +'" name="data-values['+ val +']" value="'+ val +'" />') );

        }else{

            store.find("#chbix-store-"+ val).remove();

        }
    }
}

/**
 * Check if checkbox should be checked while tadatables loading
 * @param {int} id
 * @param {sting} sTableId
 * @returns {Boolean}
 */
function fnCheckBoxItTest(id, sTableId){

    var store = $("#" + sTableId).closest(".popupAdd").find(".checkBoxIt-store");
    return store.find("#chbix-store-"+ id).length > 0;
}




/**
 * Images for categories functions
 */

/**
 *
 * @param {id} product_id
 * @param {id} category_id
 * @param {string} sTableId
 * @returns {undefined}
 */
function fnUpdateCategoryThumbnail(product_id, category_id, sTableId){
    var field = $("#" + sTableId).closest(".popupAdd").find(".categories-images .category-thumbnails");

    field.each(function() {

        var th_product_id = $(this).data('product-id');
        var th_category_id = $(this).data('category-id');
        var th_file_id = $(this).data('file-id');

        if(product_id == th_product_id && th_category_id == category_id){

            var $that = $(this);
            var aaData = {};
            aaData.ajax = true;
            aaData.product_id = product_id;
            aaData.category_id = category_id;
            aaData.file_id = th_file_id;

            fnShowLoader();

            var oResponse = $.post( base + 'assortment/products/fetchCategoryImage', aaData, function(json){                             // przesyłamy
                    fnHideLoader();

                    if(typeof json !== 'undefined' && json !== null){

                        var image_time = json.date_updated;

                        // append image
                        var $image = $('<img src="'+ base + json.image_url + '?t=' + image_time.replace(' ', '') +'" alt="Thumbnail - not found" />');
                        $that.find('.image-container').html($image);

                        $that.attr('data-file-id', json.files_id);


                        if(category_id !== -1){

                            var button_action_crop = "fnComplexActionPopup('"+base+"assortment/products/browseProductImages/" + product_id + "/" + category_id + "', [" + json.files_id + "] , 'assortmentProductImages', '"+ __lang('Przeglądaj obrazki') + "', 800 , iDefaultDialogHeight, '" + __lang('Przeglądaj obrazki') + "')";
                            var button_action_delete = "fnSimpleActionConfirm2('"+base+"assortment/products/deleteCategoryImage/"+product_id+"', ["+category_id+"], 'assortmentProductImages', '"+__lang('Usuń')+"', '"+__lang('Usuń obrazek')+"', '"+__lang('Czy na pewno usunąć obrazek?')+ "', '"+__lang('Usuń')+ "')";

                            var $button = $('<div class="button buttonHover" onclick="'+button_action_crop+'">'
                                + '<span class="name">'+ __lang('Zmień')+'</span>'
                                + '</div>'
                                + '<div class="button buttonHover" onclick="'+button_action_delete+'">'
                                + '<span class="name">'+ __lang('Usuń')+'</span>'
                                + '</div>'
                            );



                            $that.find('.buttons-container').html($button);

                        }else{

                            var button_action = "fnComplexActionPopup('"+base+"cms/files/crop/rectangle/" + product_id + "', [" + json.files_id + "] , 'assortmentProductImages', 'Crop image', 700, iDefaultDialogHeight, 'Crop',  null)"

                            var $button = $(  '<div class="button buttonHover singleButton" onclick="'+button_action+'" '
                                + '<span class="name">'+ __lang('Zmień')+'</span>'
                                + '</div>'
                            );

                            $that.find('.buttons-container').html($button);

                        }

                    }else{
                        $that.find('.image-container').html("");

                        if(category_id !== -1){
                            var button_action = "fnComplexActionPopup('"+base + "assortment/products/browseProductImages/" + product_id + "/" + category_id + "' , null , 'assortmentProductImages', '"+__lang('Przeglądaj obrazki') +"', 800 , iDefaultDialogHeight, '" + __lang('Przeglądaj obrazki') + "')";

                            var $button = $(  '<div class="button buttonCenter" onclick="'+button_action+'" '
                                + '<span class="name">'+ __lang('Dodaj')+'</span>'
                                + '</div>'
                            );

                            $that.find('.buttons-container').html($button);
                        }

                    }

                },"json"
            );
            oResponse.error(function() {
                fnConnectionError(sTableId,__lang("Error while fetching category image"));
                //aDataTables[sTableId].fnStandingRedraw();
            });
            return false;
        }
    });
}

/**
 * fnUpdateMainThumbnail
 * @param {id} product_id
 * @param {string} sTableId
 * @returns {undefined}
 */
function fnUpdateMainThumbnail(product_id, sTableId){
    return fnUpdateCategoryThumbnail(product_id, -1, sTableId);
}



function fnUpdateProductColors(product_id, langs_id, sTableId){
    var aaData = {};
    aaData.product_id = product_id;
    aaData.langs_id = langs_id;
    console.log(aaData);

    var oResponse = $.post( base + 'assortment/products/fetchColorsHmtl', aaData, function(content){                             // przesyłamy
            $("#all-colors-list." + sTableId).html(content);
        },"text"
    );

}


/**
 * Adds
 * @param {object} oObj         this
 * @param {int} iThumbId        id of thumbnail we want add to category
 * @param {int} iProductId      id of product we are in
 * @param {int} iCategoryId     id of category we want to add thumbnail
 * @param {string} sTableId     name of table we want to refresh after all
 * @returns {Boolean}
 */
function fnPopupAddImageProduct(oObj, iThumbId, iProductId, iCategoryId, sTableId){

    fnShowLoader();
    var aaData={};
    aaData.thumbnail_id = iThumbId;
    aaData.product_id = iProductId;
    aaData.category_id = iCategoryId;

    aaData.ajax = true;
    var oResponse = $.post( base + 'assortment/products/browseProductImages' , aaData, function(json){
            fnHideLoader();
            fnCheckJsonLogin(json);
            fnJsonIfSuccess(sTableId, json);
            fnJsonIfError(json);
            //aDataTables[sTableId].fnStandingRedraw();
            // also we have to refresh category thumbnails
            fnUpdateCategoryThumbnail(iProductId, iCategoryId, sTableId);

            fnClosePopUp($(oObj).closest('.dialog'), false);

        },"json"
    );
    oResponse.error(function(e) {
        console.log(e);
        fnConnectionError(sTableId, __lang("Error while adding category image"));
        //aDataTables[sTableId].fnStandingRedraw();
    });
}



/**
 * Funkcja wykonuje prostą akcje
 * @param sSource adres
 * @param aArgs argmenty
 * @param sMsg wiadomość jeśli połaczenie się nie uda
 * @param parametr parametr przekazywany do funkcji jeżeli akcja się uda
 * @param  foo funckcja wykonywana jeżeli akcja się uda
 */
function fnSimpleActionWithoutTable(sSource, aArgs, sMsg, parametr, foo){
    fnShowLoader();
    var aaData={};

    aaData.values = aArgs;
    aaData.ajax = true;
    oResponse = $.post(sSource, aaData, function (json) {                            // przesyłamy
            fnCheckJsonLogin(json);
            if( json.ok.status === true){
                $.jGrowl( json.ok.message, { group: 'alertOk' });
                if(foo !== false)
                    foo(parametr);
            }
            fnJsonIfError(json);
            fnHideLoader();
        }, "json"
    );
    oResponse.error(function () {
        fnConnectionError(null, sMsg);
    });
}

function number_format(number, decimals, dec_point, thousands_sep) {
    //  discuss at: http://phpjs.org/functions/number_format/
    // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: davook
    // improved by: Brett Zamir (http://brett-zamir.me)
    // improved by: Brett Zamir (http://brett-zamir.me)
    // improved by: Theriault
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Michael White (http://getsprink.com)
    // bugfixed by: Benjamin Lupton
    // bugfixed by: Allan Jensen (http://www.winternet.no)
    // bugfixed by: Howard Yeend
    // bugfixed by: Diogo Resende
    // bugfixed by: Rival
    // bugfixed by: Brett Zamir (http://brett-zamir.me)
    //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    //  revised by: Luke Smith (http://lucassmith.name)
    //    input by: Kheang Hok Chin (http://www.distantia.ca/)
    //    input by: Jay Klehr
    //    input by: Amir Habibi (http://www.residence-mixte.com/)
    //    input by: Amirouche
    //   example 1: number_format(1234.56);
    //   returns 1: '1,235'
    //   example 2: number_format(1234.56, 2, ',', ' ');
    //   returns 2: '1 234,56'
    //   example 3: number_format(1234.5678, 2, '.', '');
    //   returns 3: '1234.57'
    //   example 4: number_format(67, 2, ',', '.');
    //   returns 4: '67,00'
    //   example 5: number_format(1000);
    //   returns 5: '1,000'
    //   example 6: number_format(67.311, 2);
    //   returns 6: '67.31'
    //   example 7: number_format(1000.55, 1);
    //   returns 7: '1,000.6'
    //   example 8: number_format(67000, 5, ',', '.');
    //   returns 8: '67.000,00000'
    //   example 9: number_format(0.9, 0);
    //   returns 9: '1'
    //  example 10: number_format('1.20', 2);
    //  returns 10: '1.20'
    //  example 11: number_format('1.20', 4);
    //  returns 11: '1.2000'
    //  example 12: number_format('1.2000', 3);
    //  returns 12: '1.200'
    //  example 13: number_format('1 000,50', 2, '.', ' ');
    //  returns 13: '100 050.00'
    //  example 14: number_format(1e-8, 8, '.', '');
    //  returns 14: '0.00000001'

    number = (number + '')
        .replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                    .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
            .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}


/**
 * Alias edit SL product
 * @param {int} products_id
 * @param {string} uri
 * @param {string} table
 * @returns {undefined}
 */
function fnContinueEditProduct(products_id, uri, table){
    fnComplexActionPopup(base + uri, [products_id] , table, 'Edit product', 1000 , iDefaultDialogHeight, 'Edit',  null);
}

/**
 * Additional SL fx
 * @param {string} selector
 * @returns {undefined}
 */
function fnSetActiveButtonToNo(selector){
    $(selector).find("input[type=radio][value=0]").prop('checked', true).button("refresh");;
}


function fnMassLabelsAction(sSource, asKeyValue, sTableId, sMsg){

    var newValues = {};
    var bReadChb = true;

    if(asKeyValue[0] === 'selected'){
        bReadChb = true;
        newValues[0] = asKeyValue[1];

    }else{

        bReadChb = false;
        newValues.value = asKeyValue[1];
        newValues.filter = $("#filteredIDs").val();

        if(newValues.filter === ""){
            $.jGrowl("Nie wyfiltrowano żadnych rekordów" , { group: 'alertInfo' });
            return false;
        }

    }

    fnMassAction(sSource, newValues, sTableId, sMsg , bReadChb);
}

function fnUpdateStaticContainter(data, selector){
    $(document).find(selector).html(data);
}

function massivePrintSummaries( tableId ){
    var ids = fnReadCheckboxes(tableId);
    if(ids.length > 0){

        var string = ids.join('-');
        window.open(base + 'admin/sales/orders/ordertopdf/summary/' + string, '_blank');
    }else{
        $.jGrowl('Wybierz przynajmniej jeden wpis!', { sticky: false, group: 'alertError' });

    }
}

function massivePrintReceipts( tableId ){
    var ids = fnReadCheckboxes(tableId);
    if(ids.length > 0){
        var string = ids.join('-');
        window.open(base + 'admin/sales/orders/ordertopdf/receipt/' + string, '_blank');
    }else{
        $.jGrowl('Wybierz przynajmniej jeden wpis!', { sticky: false, group: 'alertError' });

    }
}

function printTable(tableName){
    divToPrint = document.getElementById(tableName);


    newWin= window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
}


function duplicateOrder( id_order ){
    //var idToCopy = fnReadCheckboxes(tableId);

    //if (idToCopy.length == 1) {

    $.post(base + "sales/orders/getorders", {'id': id_order}, function (data) {}, "json").success(function(data){

        var message = __lang('Na pewno duplikować zamówienie?');
        message += '<br/>';

        if(data['count']>0){
            message += __lang('Będzie to ') +' '+ data['count'] + ' '+ __lang('duplikat.')+'<br/>';
        }

        message += __lang('Zostanie utworzony duplikat oryginału')+ ' (' + data['original_onum'] +')<br/>';
        message += __lang('Kopia będzie nazywać się ') + ' '+data['onum'] + '<br/>';
        message += __lang('Produkty mające status backorder pozostaną w raporcie backorder! Jeśli wysyłasz te produkty użyj przycisku duplicate back order.');

        //Any items on back order will still remain in back order report! If you are sending out back order products use duplicate back order products button.




        fnSimpleActionConfirm2(
            base + 'sales/orders/copyorder',
            [data['id'], data['onum'], 0],
            'salesOrders',
            __lang('Duplikowanie zamówienia'),
            __lang('Duplikuj zamówienie'),
            message,
            __lang('Duplikuj'));


    });

    //} else {
    //	$.jGrowl(__lang('Wybierz dokładnie jeden wpis!'), {sticky: false, group: 'alertError'});
    //}

}

function duplicateBackOrder( order_id ){
    //var idToCopy = fnReadCheckboxes(tableId);
    //if (idToCopy.length == 1) {
    $.post(base + "sales/orders/getorders", {'id': order_id}, function (data) {}, "json").success(function(data){

        var message = __lang('Przygotowanie zamówienia produktów backordered');
        message += '<br/>';

        if(data['count']>0){
            message += __lang('Będzie to ') +' '+ data['count'] + ' '+ __lang('duplikat.')+'<br/>';
        }

        message += __lang('Zostanie utworzony duplikat oryginału')+ ' (' + data['original_onum'] +')<br/>';
        message += __lang('Kopia będzie nazywać się ') + ' '+data['onum'] + '<br/>';
        message += '<b>' +__lang('Zostaną skopiowane tylko produkty mające status backorder.') +'</b><br/>';
        message += __lang('Produkty backorder w oryginalnym zamówieniu zostaną skasowane, by nie pojawiały się w raporcie backorder.');
//Back order products in original order will be canceled (so they wont appear in back order products report anymore).

        fnSimpleActionConfirm2(
            base + 'sales/orders/copyorder',
            [data['id'], data['onum'], 1],
            'salesOrders',
            __lang('Duplikowanie zamówienia'),
            __lang('Duplikuj zamówienie'),
            message,
            __lang('Duplikuj'));


    });
    //
    //} else {
    //	$.jGrowl(__lang('Wybierz dokładnie jeden wpis!'), {sticky: false, group: 'alertError'});
    //}

}

//function fnSimpleActionConfirmDuplication(sSource, asId, sTableId, sMsg, sTitle, sQuestion, sConfirm){
//	var aaData;
//	aaData = fnSimpleActionCollectData(sTableId, asId);
//	if(aaData != 0){
//		fnPrepareConfirmDialogDuplication(sTitle, sQuestion);
//
//		var dialogConfirm;
//		dialogConfirm = $("#dialogConfirmDuplication").dialog({
//			open: function (event, ui) {
//				console.log(asId[2] );
//
//				if(asId[2] == 0){
//					$('#dbo #name').html(__lang('Duplikuj produkty backorder') )  ;
//					$('#dbo').click(function(){
//						fnClosePopUp(dialogConfirm, false);
//
//						duplicateBackOrder(sTableId);
//					});
//				}else{
//					$('#dbo #name').html(__lang('Duplikuj zamówienie') )  ;
//					$('#dbo').click(function(){
//						fnClosePopUp(dialogConfirm, false);
//
//						duplicateOrder(sTableId);
//					});
//				}
//
//				$('#dialogConfirmDuplicationBtnOk').html('<span class="icon ok"></span><span class="name">'+sConfirm+'</span>');
//				$('#dialogConfirmDuplicationBtnOk').click(function () {
//					fnShowLoader();
//					aaData.ajax = true;
//					oResponse = $.post(sSource, aaData, function (json) {                            // przesyłamy
//							fnHideLoader();
//							fnCheckJsonLogin(json);
//							fnJsonIfSuccess(sTableId, json);
//							fnJsonIfError(json);
//							aDataTables[sTableId].fnStandingRedraw(null);
//
//							try{
//								if(typeof json !== 'undefined' && json != null){
//									if(typeof json.callback !== 'undefined' && typeof json.callback.method !== 'undefined'){
//										fnCallback(json.callback);
//									}
//								}
//							}catch(e){
//								console.log(e);
//							}
//
//						}, "json"
//					);
//					oResponse.error(function () {
//						fnConnectionError(sTableId,sMsg);
//					});
//					fnClosePopUp(dialogConfirm, false)
//				});
//				$('#dialogConfirmDuplicationBtnCancel').click(function () {
//					fnClosePopUp(dialogConfirm, false)
//				});
//			},
//			resizable: false,
//			modal: true,
//			minHeight: '100px',
//			closeOnEscape: true
//		});
//	}
//}

function fnAddTask(id){
    if($('#client').val() == ''  && $('#error_tool_tip').length == 0 ){
        $('#client').addClass('error');
        var error_tool_tip = '<div class="icon error tooltip" id="error_tool_tip" title="Klient" ></div>';
        $('#client_autocomplete').after(error_tool_tip);
    }

    else if($('#client').val() != ''){
        $('#client').removeClass('error');
        $('#error_tool_tip').remove();
        $.ajax({
            url: "Order/addTask",
            type: "POST",
            dataType: 'json',
            data: {
                'parametr': {id: id
                }
            },
            success: function (data) {
                $.jGrowl( data.status.ok.message, { group: 'alertOk' });                       // komunikat

                var options = $(".task-space");
                options.append(data.task_space);

                var script = document.createElement( 'script' );
                script.type = 'text/javascript';
                script.src = base + 'js/selectBoxIt.js';
                $(".task-space").append( script );

                var script2 = document.createElement( 'script' );
                script2.type = 'text/javascript';
                script2.src = base + 'js/scripts.js';
                $(".task-space").append( script2 );

                var script3 = document.createElement( 'script' );
                script3.type = 'text/javascript';
                script3.src = base + 'js/functions.js';
                $(".task-space").append( script3 );



                $('.selectboxit-container').attr('width', 148);

                aDataTables['ordersOrders'].fnStandingRedraw(null);




            },
            error: function (data) {
                console.log('error');

            }
        });
    }








}
function fnAddStage(data, task_id){

    var options = $(".stages_for_task_"+task_id+"");
    options.append(data);


    var script = document.createElement( 'script' );
    script.type = 'text/javascript';
    script.src = base + 'js/selectBoxIt.js';
    options.append( script );

    var script2 = document.createElement( 'script' );
    script2.type = 'text/javascript';
    script2.src = base + 'js/scripts.js';
    options.append( script2 );

    statusStageCheckout();






}

function statusStageCheckout(){
    $('.field-input-details').change(function(e){
        $.post(base + 'order/changeStageStatus', {
            stage_id : $(this).attr('stageID'),
            stage_status : $(this).val(),
            order_id : $('#order_id').val()
    }, function(data) {
        var response = $.parseJSON(data);
        console.log(response.status);
        $('#order_status_replace').html(response.status);
        aDataTables['ordersOrders'].fnStandingRedraw(null); //uaktualnienie tabeli ze wszystkimi zamówieniami
        $.jGrowl(response.msg);
    });
})
}




function fnAddSpecialField(variant_id, inner_id){
    $.ajax({
        url: "Order/addSpecialField",
        type: "POST",
        dataType: 'json',
        data: {
            'parametr': {
                variant_id: variant_id,
                inner_id: inner_id
            }
        },
        success: function (data) {
            var icon = $('#stageFieldSpecIcon_'+variant_id+'_'+inner_id+'');
            icon.removeClass('plus').addClass('x');

            var button = $('#stageFieldSpecAction_'+variant_id+'_'+inner_id+'');
            button.prop('onclick',null).off('click');
            button.attr('onclick','removeSpecField('+variant_id + ','+ inner_id  +')');

            var block = $('#field_'+variant_id+'_'+inner_id+'');
            block.after(data);
            console.log('succes');
        },
        error: function (data) {
            console.log('error');

        }
    });

}

function fnAddSpecialFieldToForm(variant_group_id, sort_id){

    $.ajax({
        url: "Order/PrepareSpecialFieldForForm",
        type: "POST",
        dataType: "json",
        data:{
            'variant_group_id' : variant_group_id,
            'sort_id' :sort_id
        },
        success: function(data){
            $(".field_"+variant_group_id).last().find('.addButton_'+sort_id+'').css('visibility', 'hidden')
           // console.log($(".field_"+variant_group_id).last().find('.addButton_'+sort_id+''));
            $(".field_"+variant_group_id).last().after(data);

        }
    });



}

function fnRemoveSpecialFieldForm(button, variant_group_id, option_id, sort_id){
/*
    if(option_id > -1 ){ //jeśli istnieje w bazie to usuwamy
        $.ajax({
            url: "Order/DeleteVariant",
            type: "POST",
            dataType: "json",
            data:{
                'option_id': option_id
            }
        })
    }
    */
    var field = '<input type="hidden" name="option_id_'+ option_id +'" value="'+ option_id +'"  >';
    $('#fields_to_remove').append(field);
    var name = $('.field_'+variant_group_id).first().find('label').html();
    button.parent().remove();
    $(".field_"+variant_group_id).first().find('label').html(name);
    $(".field_"+variant_group_id).last().find('.addButton').css('visibility', 'visible')
}

function removeSpecField(variant_id, inner_id){
    $('#field_'+variant_id+'_'+inner_id+'').remove();

}

function fnReloadVariantSection(view, stageid){

    $("#stagevariants_"+stageid).empty();
    $("#stagevariants_"+stageid).append(view);
    /*
    var script4   = document.createElement("script");
    script4.type  = "text/javascript";
    script4.text  = "sum_dimesnions();";
    $(".task-space").append( script4 );
*/
}


function fnReloadTaskFiles(data, task_id){
    $("#output_task_files"+task_id).html(data);

}


function fnRemoveTask(id){
    $('.task_id_'+id+'').remove();

}
/*
 function fnAddStage(task_id){
 var stage = $('#task_id_'+task_id+ ' option:selected').val();
 $.ajax({
 url: "Order/addStage",
 type: "POST",
 dataType: 'json',
 data: {
 'parametr': {task_id: task_id,
 stage: stage
 }
 },
 success: function (data) {
 var options = $(".stages_for_task_"+task_id+"");
 options.append(data);
 console.log('succes');
 },
 error: function () {
 console.log('error');

 }
 });
 }
 */
function fnPrintOrder(id, mode){
    url = base + '/pdf/' + id + '/'+mode;
    window.open(url, '_blank');
}




function sum_dimesnions(){
    var $tasks = $("div[id^='task_id_']");
    var $allstages = $tasks.find("div[id^='stage_id_']");
    $allstages.each(function ()
    {

        var $field_type = 0;
        var $all = $(this).find("input[id^='special_field_sum_']");
        $all.each(function ()
        {
            $field_type = parseFloat($field_type) + parseFloat($(this).val() );

        });
        $(this).append('<div class="dimension_sum"><b>Razem:</b> '+ $field_type + 'm<sup>2</sup></div>');
    });
}



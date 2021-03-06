
$(document).ready(function(){
    
    $('#vsectionDef').click(function(e){
        e.preventDefault();
        if (typeof defaultVsection !== 'undefined'){
            $('#mainForm_vsection').val(defaultVsection);
        }
    });
     //Color pickers
    $('.form-colorpicker').colorpicker();
    
    $('.form-colorpicker').colorpicker().on('changeColor', function(ev){
        $(this).css('background-color', ev.color.toHex());
        $('#mainForm_color').val(ev.color.toHex());
     });
    
    var trajectoryType;
    var trajectoryVariant;
    
    $('#mainForm_wellTrajectory').popover();
    
    function clearFieldsValue(){
        $("#mainForm_A").val("");
        $("#mainForm_A1").val("");
        $("#mainForm_A2").val("");
        $("#mainForm_A3").val("");
        $("#mainForm_A4").val("");
        
        $("#mainForm_H").val("");
        $("#mainForm_H1").val("");
        $("#mainForm_H2").val("");
        $("#mainForm_H3").val("");
        $("#mainForm_H4").val("");
        
        $("#mainForm_L").val("");
        $("#mainForm_L1").val("");
        $("#mainForm_L2").val("");
        $("#mainForm_L3").val("");
        $("#mainForm_L4").val("");
        $("#mainForm_L5").val("");
        
        $("#mainForm_R").val("");
        $("#mainForm_R1").val("");
        $("#mainForm_R2").val("");
        $("#mainForm_R3").val("");
        $("#mainForm_R4").val("");
        
        $("#mainForm_alfa").val("");
        $("#mainForm_alfa1").val("");
        $("#mainForm_alfa2").val("");
        $("#mainForm_alfa3").val("");
        $("#mainForm_alfa4").val("");
        
        $("#mainForm_delta").val("");
        $("#mainForm_delta1").val("");
        $("#mainForm_delta2").val("");
        $("#mainForm_delta3").val("");
        $("#mainForm_delta4").val("");
        
        $("#mainForm_DLS").val("");
        $("#mainForm_DLS1").val("");
        $("#mainForm_DLS2").val("");
        $("#mainForm_DLS3").val("");
        $("#mainForm_DLS4").val("");
        
        $("#mainForm_Q").val("");
        $("#mainForm_ro").val("");
        
        $(".varField").hide();
        
    }
    
    function showFormFields(trajectoryType){
        var fields = trajectoryType.split('_');
        console.log(fields);
        fields.shift();
        console.log(fields);
        $.each(fields, function(i,val){
            console.log(val);
            $("#grp_" + val).show(); 
        });
    }
    
    //tabsClic
    
    $("#atrtab1Depth").on("click", function(){
        
        var actionAddress = $('#mainForm').attr('action');
        actionAddress = actionAddress + "#tab1Depth";
        $('#mainForm').attr('action', actionAddress);
    });
    
   $("#mainForm_wellType").on('change', function(){
        if ($(this).val() == '2d'){
            $('#mainForm_wellTrajectory').prop('disabled', false);
            $("#grp_variant_" + trajectoryType).show();
            $(".trajectory2D").show();
            $(".trajectory3D").hide();
            var trajectoryVariant = $("#mainForm_wellTrajectoryVariant").val();
            showFormFields(trajectoryVariant);
        }
        if ($(this).val() == '3d'){
            $('#mainForm_wellTrajectory').prop('disabled', 'disabled');
            $('.varField').hide();
            $(".grpVariants").hide();
            $(".trajectory2D").hide();
            $(".trajectory3D").show();
            
        }
    });
    
    if(window.location.href.indexOf("#tab1Depth") > -1){
        $('#tab-01').find("li").removeClass('active');
        $(".tab-content").find(".tab-pane").removeClass('active');
        $('#atrtab1Depth').addClass("active");
        $('.tab-content').find("#tab1Depth").addClass("active");
    }
    
    if ($('#mainForm_wellType').length){
        
        $(".grpVariants").hide();
        var wellType =  $('#mainForm_wellType').val();
        if (wellType == '2d'){
            $('#mainForm_wellTrajectory').prop('disabled', false);
            $("#grp_variant_" + trajectoryType).show();
            $(".trajectory2D").show();
            $(".trajectory3D").hide();
            var trajectoryVariant = $("#mainForm_wellTrajectoryVariant").val();
            showFormFields(trajectoryVariant);
        }
        else{
            $('#mainForm_wellTrajectory').prop('disabled', 'disabled');
            $('.varField').hide();
            $(".grpVariants").hide();
            $(".trajectory2D").hide();
            $(".trajectory3D").show();
        }
    }
    
    
    if ($('#mainForm_wellTrajectory').length && $('#mainForm_wellType').val() == '2d'){
        var tarjectory =  $('#mainForm_wellTrajectory').val();
        
        if (tarjectory == "J1") $("#grp_variant_j1").show();
        if (tarjectory == "J2") $("#grp_variant_j2").show();
        if (tarjectory == "J3") $("#grp_variant_j3").show();
        if (tarjectory == "J4") $("#grp_variant_j4").show();
        if (tarjectory == "S1") $("#grp_variant_s1").show();
        if (tarjectory == "S2") $("#grp_variant_s2").show();
        if (tarjectory == "S3") $("#grp_variant_s3").show();
        if (tarjectory == "S4") $("#grp_variant_s4").show();
        if (tarjectory == "catenary") $("#grp_variant_catenary").show();
        trajectoryType = tarjectory.toLowerCase();
        trajectoryVariant = $("#mainForm_wellTrajectoryVariant").val();
        showFormFields(trajectoryVariant);
        console.log(trajectoryType);
        
        $("#grp_variant_" + trajectoryType).on('change', function(){
            trajectoryVariant = $("#grp_variant_" + trajectoryType).find('option:selected').attr("name")
            console.log(trajectoryVariant);
            $("#mainForm_wellTrajectoryVariant").val(trajectoryVariant);
            console.log($("#mainForm_wellTrajectoryVariant").val());
            
            clearFieldsValue();
            $(".varField").hide();
            showFormFields(trajectoryVariant);
            
        });
        
    }
    
   $('#mainForm_wellTrajectory').focus(function(e){
       console.log($(this).val());
       $('#mainForm_wellTrajectory').data("original-title", $(this).val());
       //$('#mainForm_wellTrajectory').data("content", '<img src="assets/img/paths/3drill_infographics-02.gif" />');
       $('.popover-title').html($(this).val());
       $('#mainForm_wellTrajectory').popover('destroy');
       var nameOfTrajectory = $(this).val()
       $('#mainForm_wellTrajectory').popover({'original-title': nameOfTrajectory, 'title': nameOfTrajectory, 'content':'<img src="assets/img/paths/3drill_' + nameOfTrajectory + '.gif">', html:"true"});
       
       $('#mainForm_wellTrajectory').popover('show');
       
       
       
   });
   
   $('#mainForm_wellTrajectory').on( "change", function( event, ui ) {
        var nameOfTrajectory = $(this).val();
        $('#mainForm_wellTrajectory').popover('hide');
       $('#mainForm_wellTrajectory').popover('destroy');
       $('#mainForm_wellTrajectory').popover({'original-title': nameOfTrajectory, 'title': nameOfTrajectory, 'content':'<img src="assets/img/paths/3drill_' + nameOfTrajectory + '.gif">', html:"true"});
       
       $('#mainForm_wellTrajectory').popover('show');
       console.log($(this).val());
   } );
       
   
   
    
    // zmiana Trajektorii /OK
    $('#mainForm_wellTrajectory').on('change', function(){  
        trajectoryType = $('#mainForm_wellTrajectory').val().toLowerCase();
        
        $(".grpVariants").hide();
        $(".varField").hide();
        $("#grp_variant_" + trajectoryType).show();
        trajectoryVariant = $("#grp_variant_" + trajectoryType).find('option:selected').attr("name");
        $("#mainForm_wellTrajectoryVariant").val(trajectoryVariant);
        showFormFields(trajectoryVariant);
        
        
        // zmiana Wariantów + zapis do pola variants
        $("#grp_variant_" + trajectoryType).on('change', function(){
            trajectoryVariant = $("#grp_variant_" + trajectoryType).find('option:selected').attr("name");
            console.log(trajectoryVariant);
            $("#mainForm_wellTrajectoryVariant").val(trajectoryVariant);
            console.log($("#mainForm_wellTrajectoryVariant").val());
            
            clearFieldsValue();
            showFormFields(trajectoryVariant);
            
        });
        
        console.log(trajectoryType);
    });

    
    
    //Pokazywanie pól
    
    
    
    $('.nocsroll').bind('mousewheel DOMMouseScroll', function(e) {
    var scrollTo = null;

    if (e.type == 'mousewheel') {
        scrollTo = (e.originalEvent.wheelDelta * -1);
    }
    else if (e.type == 'DOMMouseScroll') {
        scrollTo = 40 * e.originalEvent.detail;
    }

    if (scrollTo) {
        e.preventDefault();
        $(this).scrollTop(scrollTo + $(this).scrollTop());
    }
    });
    
    if (emptyWell == 1){
        trajectoryVariant = 'j1_A_H_L1';
        $("#mainForm_wellTrajectoryVariant").val(trajectoryVariant);
        showFormFields(trajectoryVariant);
    }
    
});

$(function () {
    // Initialize appendGrid
    $('#tblAppendGrid').appendGrid({
        caption: 'Points',
        initRows: 1,
        columns: [
                { name: 'X', display: 'East', type: 'text', ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '100px'}, ctrlClass: 'required' },
                { name: 'Y', display: 'North', type: 'text', ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '100px'}, ctrlClass: 'required' },
                { name: 'Z', display: 'TVD', type: 'text', ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '100px'}, ctrlClass: 'required' },
                { name: 'LP', display: 'Straight', type: 'text', ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '100px'}, ctrlClass: 'required' },
                { name: 'alfa', display: 'Inclination', type: 'text', ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '100px'}, ctrlClass: 'required',
                onClick: function (evt, rowIndex) {
                    /*var trajectory3dType = $("#mainForm_well3DTrajectory").val();
                    if (rowIndex > 1 && trajectory3dType === 'POCZ'){
                       // this.val("");
                       $(evt.currentTarget).val("");
                       $(evt.currentTarget).attr("disabled",true);
                       
                    } */
                    
                }},
                { name: 'beta', display: 'Azimuth', type: 'text', ctrlAttr: { maxlength: 10 }, ctrlCss: { width: '60px'}, ctrlClass: 'required',
                //onClick: function (evt, rowIndex) {}
                },
            ],
        customGridButtons: {
            // Use jQuery UI Button initial parameter
            append: $('<button/>').addClass('btn btn-primary btn-small').text('Add').get(0),
            removeLast: $('<button/>').addClass('btn btn-danger btn-small').text('Remove Last').get(0),
            // Use a DOM element
            insert: $('<button/>').addClass('btn btn-primary btn-small').html("<i class='fa fa-plus'></i>").get(0),
            //remove: $('<button/>').text('-').get(0),
            remove: $('<button/>').addClass('btn btn-danger btn-small').html("<i class='fa fa-minus'></i>").get(0),
            // Use a function that create DOM element
            moveUp: $('<button/>').addClass('btn btn-default btn-small').html("<i class='fa fa-toggle-up'></i>").get(0),
            moveDown: $('<button/>').addClass('btn btn-default btn-small').html("<i class='fa fa-toggle-down'></i>").get(0)
        },
        afterRowInserted: function(caller, parentRowIndex, addedRowIndex){
            //alert(addedRowIndex);
            //console.log(caller);
           /* if (addedRowIndex > 1){
                $('input[id*=alfa]:visible').each( function(value, index){
                    var id = $(index).attr('id').replace("tblAppendGrid_alfa_","");
                    var trajectory3dType = $("#mainForm_well3DTrajectory").val();
                    if (parseInt(id) > 2 && trajectory3dType === 'POCZ'){
                        $(index).val("");
                        $(index).attr("disabled",true);
                        $(index).css({'background-color':'#eeeeee'});
                    } 
                });
                $('input[id*=beta]:visible').each( function(value, index){
                    var id = $(index).attr('id').replace("tblAppendGrid_beta_","");
                    var trajectory3dType = $("#mainForm_well3DTrajectory").val();
                    if (parseInt(id) > 2 && trajectory3dType === 'POCZ'){
                        $(index).val("");
                        $(index).attr("disabled",true);
                        $(index).css({'background-color':'#eeeeee'});
                    } 
                });
            }*/
           //alert("X");  
        }
        
        
    });
    if (points3d !== null){
        console.log(points3d);
         $('#tblAppendGrid').appendGrid('load',points3d);
    }
    
    $("#tblAppendGrid").on('change', ".required", function(){
        $(this).val($(this).val().replace(',', '.'));
        if ($.isNumeric($(this).val())){
            $(this).removeClass('error');
        }
        else{
            $(this).addClass('error');
        }
    });
    
    
    // Handle `Serialize` button click
    //$('#btnSerialize').button().click(function () {
    $('#mainForm__action_Save').on("click", function (e) {
        e.preventDefault();
        var form = $(document.forms[0]);
        var formValues = $(document.forms[0]).serializeArray();
        var allFieldAreNumbers = true;
        if ($("#mainForm_wellType").val() == '3d'){
        var objectToJson = {};
        $.each(formValues, function(key, value){
            
            if (value.name.indexOf('tblAppendGrid')>=0 ){
              console.log(key);
              console.log(value); 
                if (objectToJson [this.name] !== undefined) {
                    if (!objectToJson[this.name].push) {
                        objectToJson[this.name] = [objectToJson [this.name]];
                    }
                    objectToJson[this.name].push(this.value || '');
                } else {
                    objectToJson[this.name] = this.value || '';
                }
                if (!$.isNumeric(objectToJson[this.name]) && this.name !="tblAppendGrid_rowOrder"){
                    $("#" + this.name).addClass('error');
                    allFieldAreNumbers = false;
                }
            }
            
        });
        console.log(objectToJson);
        var wellId = $('#mainForm_id').val();
        console.log(wellId );
        //if (wellId !== ''){
            if ( allFieldAreNumbers == true){
                
                $("#mainForm_well3DPints").val($.toJSON(objectToJson));
                form.submit();
                
                
                /*
                $.ajax({
                    type: "POST",
                    url: BaseURL + "?_m=Well&_o=Ajax3DPoints",
                    beforeSend: function(){
                        
                    },
                    complete: function(){
                        
                    },
                    data: {
                        wellId: wellId,
                        wellType: '3d',
                        wellPoints: $.toJSON(objectToJson)
                    }
                }).done(function( data, textStatus, jqXHR ) {
                        if (data['status'] == 'OK') {
                            form.submit();
                        }    
                    });
                */   
                    
                }
            //}
            else{
                form.submit();
            }
                
        } // end of 3D
        
        
        if ($("#mainForm_wellType").val() == '2d'){
            form.submit();
        }
        
        
    
        
        //alert('Here is the serialized data!!\n' + $(document.forms[0]).serializeArray());
    });
    
   
});
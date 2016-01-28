if (!Detector.webgl)
    Detector.addGetWebGLMessage();

var wykres={};

/*{{{ usunięte globalne zmienne
 var container;
 var camera, controls, scene, renderer, mesh;

 var cameraO, cameraP;

 var group;

 var etykieta_wykresu;
 var etykieta_wykresu2;

 var sprite1;
 var canvas1, context1, texture1;

 var colorSymbolPln=0x999999;

 var camera_x_pos=true;//*
 var camera_z_pos=true;//*
 var camera_y_pos=true;//*

 var sciana_x_poz=0;
 var sciana_y_poz=0;
 var sciana_z_poz=0;

 var grupa_liczby_x;
 var grupa_liczby_y;
 var grupa_liczby_z;

 var grupa_liczby_x_pos;
 var grupa_liczby_x_neg;
 var grupa_liczby_x_ortho;

 var grupa_liczby_y_pos;
 var grupa_liczby_y_neg;
 var grupa_liczby_y_ortho;

 var grupa_liczby_z_pos;
 var grupa_liczby_z_neg;
 var grupa_liczby_z_ortho;

 var grupa_rzut_x;
 var grupa_rzut_y;
 var grupa_rzut_z;

 var grupa_na_scianach_punkty;
 var grupa_punkty_charakterystyczne;
 var grupa_punkty_charakterystyczne_opis;

 var grupa_opis_otworu;

 var menu_1=1;//(punkty charakterystyczne i linie rzutujące)

 var menu_2=1;//(krzyżyki na ścianach, rzuty na poszczególne ściany)
 var menu_2_1=1;//north
 var menu_2_2=1;//east
 var menu_2_3=1;//tvd

 var menu_3=1;//(wyświetlanie nazw otworów)
 var menu_4=1;//(opisy punktów charakterystycznych)

 var menu_camera=1;//PerspectiveCamera:1
 //OrthographicCamera:2

 //var grupa_box;

 }}}*/
wykres.camera_x_pos=true;
wykres.camera_z_pos=true;
wykres.camera_y_pos=true;

wykres.sciana_x_poz=0;
wykres.sciana_y_poz=0;
wykres.sciana_z_poz=0;

wykres.menu_1=1;//(punkty charakterystyczne i linie rzutujące)

wykres.menu_2=1;//(krzyżyki na ścianach, rzuty na poszczególne ściany)
wykres.menu_2_1=1;//north
wykres.menu_2_2=1;//east
wykres.menu_2_3=1;//tvd

wykres.menu_3=1;//(wyświetlanie nazw otworów) 
wykres.menu_4=1;//(opisy punktów charakterystycznych) 

wykres.menu_camera=1;//PerspectiveCamera:1
//OrthographicCamera:2
wykres.colorSymbolPln=0x999999;

wykres.set_menu=function()//{{{
//function set_menu()
{
    if(wykres.menu_2_1==1&&wykres.menu_2_2==1&&wykres.menu_2_3==1)
        wykres.menu_2=1;

    if(wykres.menu_2_1==0&&wykres.menu_2_2==0&&wykres.menu_2_3==0)
        wykres.menu_2=0;
//  <----punkty charakterystyczne i linie rzutujące---->
    if(wykres.menu_1==1)
        $('#PChILR_1').prop('checked',true);
    else
        $('#PChILR_2').prop('checked',true);
//  <----krzyżyki na ścianach, rzuty na poszczególne ściany---->
    if(wykres.menu_2==1){
        $('#KnS_1').prop('checked',true);
        $('.KnS_directors').show();
    }
    else{
        $('#KnS_2').prop('checked',true);
        $('.KnS_directors').hide();
    }
//  <----krzyżyki na ścianach, rzuty na poszczególne ściany  - OSIE ---->

    if(wykres.menu_2_1==1){
        $("#KnS_north").parent().addClass('ez-checked');
        $("#KnS_north").attr('checked',true);
    }
    else{
        $("#KnS_north").attr('checked',false);
        $("#KnS_north").parent().removeClass('ez-checked');
    }


    if(wykres.menu_2_2==1){
        $("#KnS_east").parent().addClass('ez-checked');
        $("#KnS_east").attr('checked',true);
    }

    else{
        $("#KnS_east").attr('checked',false);
        $("#KnS_east").parent().removeClass('ez-checked');
    }

    if(wykres.menu_2_3==1){
        $("#KnS_tvd").parent().addClass('ez-checked');
        $("#KnS_tvd").attr('checked',true);
    }
    else{
        $("#KnS_tvd").attr('checked',false);
        $("#KnS_tvd").parent().removeClass('ez-checked');
    }

    //  <----Names of wells---->
    if(wykres.menu_3==1){
        $('#NoW_1').prop('checked',true);
    }
    else{
        $('#NoW_2').prop('checked',true);
    }



    //  <----Landmark---->
    if(wykres.menu_4==1)
        $('#pktchar_1').prop('checked',true);
    else
        $('#pktchar_2').prop('checked',true);


    if(wykres.menu_camera==2)
    {
        $('#camera_2').prop('checked',false);
        $("#camera_north").attr('checked',false);
        $("#camera_east").attr('checked',false);
        $("#camera_tvd").attr('checked',false);

    }
    else
    {
        $('#camera_1').prop('checked',true);
        $('#camera_north').parent().removeClass('ez-checked');
        $("#camera_north").attr('checked',false);

        $("#camera_east").parent().removeClass('ez-checked');
        $("#camera_east").attr('checked',false);

        $("#camera_tvd").parent().removeClass('ez-checked');
        $("#camera_tvd").attr('checked',false);

    }

} //}}}
wykres.opis_otworu=function(flg)//{{{
{
    var j;

    if(flg)
    {
        wykres.scene.remove(wykres.grupa_opis_otworu);

        wykres.grupa_opis_otworu = new THREE.Object3D();

        for(j=0;j<mbox.length;j++)
        {
            if(mdata_wybrane[j]==1)
            {
                //grupa_opis_otworu
                //headtitle wykorzystuje współrzędne pierwszego punktu tablicy box
                var spritey = wykres.makeTextSprite( mdata_o[j], {
                    fontsize: 28,
                    color: 'black',

                    borderColor: {r:0, g:0, b:0, a:1.0},
                    backgroundColor: {r:255, g:100, b:100, a:0.8}});

                //var headtitle3dp = new THREE.Vector3(mbox[j][0].x+100,mbox[j][0].y+100,mbox[j][0].z);
                var headtitle3dp = new THREE.Vector3(mbox[j][0].x+100,mbox[j][0].y+100+j*30,mbox[j][0].z);

                spritey.position.set(headtitle3dp.x,headtitle3dp.y,headtitle3dp.z);

                wykres.grupa_opis_otworu.add(spritey);

                //linia do headtitle
                var material = new THREE.LineBasicMaterial({
                    color: 0x666666
                });

                var origin=headtitle3dp;
                var terminus=mbox[j][0];
                var direction = new THREE.Vector3().subVectors(terminus,origin).normalize();
                var arrow = new THREE.ArrowHelper(direction,origin,140,0x4466aa);
                wykres.grupa_opis_otworu.add(arrow);
            }
        }

        wykres.scene.add(wykres.grupa_opis_otworu);
    }
    else
    {
        wykres.grupa_opis_otworu = new THREE.Object3D();

        //grupa_opis_otworu
        //headtitle wykorzystuje współrzędne pierwszego punktu tablicy box
        var spritey = wykres.makeTextSprite( "Dwernik-7B", {
            fontsize: 28,
            color: 'black',

            borderColor: {r:0, g:0, b:0, a:1.0},
            backgroundColor: {r:255, g:100, b:100, a:0.8}});

        var headtitle3dp = new THREE.Vector3(box[0].x+100,box[0].y+100,box[0].z);

        spritey.position.set(headtitle3dp.x,headtitle3dp.y,headtitle3dp.z);

        wykres.grupa_opis_otworu.add(spritey);

        //linia do headtitle
        var material = new THREE.LineBasicMaterial({
            color: 0x666666
        });

        var origin=headtitle3dp;
        var terminus=box[0];
        var direction = new THREE.Vector3().subVectors(terminus,origin).normalize();
        var arrow = new THREE.ArrowHelper(direction,origin,140,0x4466aa);
        wykres.grupa_opis_otworu.add(arrow);

        /* linia
         var geometry = new THREE.Geometry();
         geometry.vertices.push(
         box[0],
         headtitle3dp);

         var line = new THREE.Line(geometry,material);
         wykres.scene.add(line);
         */

        wykres.scene.add(wykres.grupa_opis_otworu);
    }
}//}}} 
wykres.orientuj_napisy_osi_y=function()//{{{
{

    if(wykres.menu_camera==1)
    {
        //liczby osi 'y', zależne od pozycji 'x' kamery, tvd
        if(wykres.camera.position.x<wykres.sciana_x_poz*100&&wykres.camera_y_pos)
        {
            wykres.scene.remove(wykres.grupa_liczby_y_pos);
            wykres.scene.add(wykres.grupa_liczby_y_neg);

            wykres.camera_y_pos=false;
        }

        if(wykres.camera.position.x>wykres.sciana_x_poz*100&&!wykres.camera_y_pos)
        {
            wykres.scene.remove(wykres.grupa_liczby_y_neg);
            wykres.scene.add(wykres.grupa_liczby_y_pos);

            wykres.camera_y_pos=true;
        }

    }

    if(wykres.menu_camera==2)
    {
        if((Math.round(wykres.camera.rotation._x*100)==0)&&
            (Math.round(wykres.camera.rotation._y*100)==0)&&
            (Math.round(wykres.camera.rotation._z*100)==0))
        {
            wykres.scene.remove(wykres.grupa_liczby_y_neg);
            wykres.scene.remove(wykres.grupa_liczby_y_pos);

            wykres.scene.add(wykres.grupa_liczby_y_ortho);
        }
        else
        {
            wykres.scene.remove(wykres.grupa_liczby_y_ortho);
            wykres.scene.add(wykres.grupa_liczby_y_pos);
        }
    }

}//}}} 
wykres.orientuj_napisy_osi_x=function()//{{{
{
    if(wykres.menu_camera==2)
    {
        if((Math.round(wykres.camera.rotation._x*100)==0)&&
            (Math.round(wykres.camera.rotation._y*100)==0)&&
            (Math.round(wykres.camera.rotation._z*100)==0))
        {
            wykres.scene.remove(wykres.grupa_liczby_x_neg);
            wykres.scene.remove(wykres.grupa_liczby_x_pos);

            wykres.scene.add(wykres.grupa_liczby_x_ortho);
        }
        else
        {
            wykres.scene.remove(wykres.grupa_liczby_x_ortho);
            wykres.scene.remove(wykres.grupa_liczby_x_pos);

            wykres.scene.add(wykres.grupa_liczby_x_neg);
        }
    }

    if(wykres.menu_camera==1)
    {
        //liczby osi 'x', zależne od wartości obrotu 'z' kamery, north
        if(wykres.camera.rotation._z<0&&wykres.camera_x_pos)
        {
            wykres.scene.remove(wykres.grupa_liczby_x_pos);
            wykres.scene.add(wykres.grupa_liczby_x_neg);
            wykres.camera_x_pos=false;
        }

        if(wykres.camera.rotation._z>0&&!wykres.camera_x_pos)
        {
            wykres.scene.remove(wykres.grupa_liczby_x_neg);
            wykres.scene.add(wykres.grupa_liczby_x_pos);
            wykres.camera_x_pos=true;
        }
    }

}//}}}
wykres.orientuj_napisy_osi_z=function()//{{{
{
    //liczby osi 'z', zależne od wartości obrotu 'z' kamery, east
    if(wykres.menu_camera==2)
    {

        if((Math.round(wykres.camera.rotation._x*100)==Math.round(-Math.PI/2*100))&&
            (wykres.camera.rotation._y==0)&&
            (wykres.camera.rotation._z==0))
        {
            wykres.scene.remove(wykres.grupa_liczby_z_neg);
            wykres.scene.remove(wykres.grupa_liczby_z_ortho);

            wykres.scene.add(wykres.grupa_liczby_z_pos);
        }

        if((Math.round(wykres.camera.rotation._x*100)==0)&&
            (wykres.camera.rotation._z==0))
        {
            wykres.scene.remove(wykres.grupa_liczby_z_neg);
            wykres.scene.remove(wykres.grupa_liczby_z_pos);

            wykres.scene.add(wykres.grupa_liczby_z_ortho);
        }
        else
        {
            wykres.scene.remove(wykres.grupa_liczby_z_ortho);
            wykres.scene.remove(wykres.grupa_liczby_z_neg);
            wykres.scene.add(wykres.grupa_liczby_z_pos);
        }

    }

    if(wykres.menu_camera==1)
    {
        if(((wykres.camera.rotation._z<Math.PI/2)&&(wykres.camera.rotation._z>-Math.PI/2))&&wykres.camera_z_pos)
        {
            wykres.scene.remove(wykres.grupa_liczby_z_neg);
            wykres.scene.add(wykres.grupa_liczby_z_pos);

            wykres.camera_z_pos=false;
        }

        if(((wykres.camera.rotation._z>Math.PI/2)||(wykres.camera.rotation._z<-Math.PI/2))&&!wykres.camera_z_pos)
        {
            wykres.scene.remove(wykres.grupa_liczby_z_pos);
            wykres.scene.add(wykres.grupa_liczby_z_neg);

            wykres.camera_z_pos=true;
        }
    }
}//}}}
wykres.nastapila_zmiana_sciana_xyz=function()//{{{
{
//usunąć przypisane grupy liczb

    if(wykres.camera_y_pos)
        wykres.scene.remove(wykres.grupa_liczby_y_pos);
    else
        wykres.scene.remove(wykres.grupa_liczby_y_neg);

    if(wykres.camera_x_pos)
        wykres.scene.remove(wykres.grupa_liczby_x_pos);
    else
        wykres.scene.remove(wykres.grupa_liczby_x_neg);

    if(wykres.camera_z_pos)
        wykres.scene.remove(wykres.grupa_liczby_z_neg);
    else
        wykres.scene.remove(wykres.grupa_liczby_z_pos);

//wygenerować na nowo grupy liczb

    wykres.pre_generuj_grupy_liczby_osi();

//przypisać grupy liczb do sceny
    if(wykres.camera_y_pos)
        wykres.scene.add(wykres.grupa_liczby_y_pos);
    else
        wykres.scene.add(wykres.grupa_liczby_y_neg);

    if(wykres.camera_x_pos)
        wykres.scene.add(wykres.grupa_liczby_x_pos);
    else
        wykres.scene.add(wykres.grupa_liczby_x_neg);

    if(wykres.camera_z_pos)
        wykres.scene.add(wykres.grupa_liczby_z_neg);
    else
        wykres.scene.add(wykres.grupa_liczby_z_pos);

//usunąć box
//usunąć linie wewnętrzne
    wykres.scene.remove(wykres.grupa_box);

//wygenerować box
//wygenerować linie wewnętrzne
    wykres.generuj_grupa_box();

//przypisać box do sceny
//przypisać linie wewnętrzne do sceny
    wykres.scene.add(wykres.grupa_box);

    renderer.render(wykres.scene,wykres.camera);
}//}}}
wykres.generuj_grupa_box=function()//{{{
{
    var colorBox1=0x999999;
    var colorBox2=0xdddddd;

    wykres.grupa_box = new THREE.Object3D();

// wewnętrzne linie	
    for(i=100;i<1000;i+=100)
    {
        //xy
        var lineGeometry = new THREE.Geometry();
        var vertArray = lineGeometry.vertices;
        vertArray.push(new THREE.Vector3(   0,i,wykres.sciana_z_poz*100));
        vertArray.push(new THREE.Vector3(1000,i,wykres.sciana_z_poz*100));
        lineGeometry.computeLineDistances();

        var lineMaterial = new THREE.LineBasicMaterial({color: colorBox2});
        var line = new THREE.Line(lineGeometry,lineMaterial);
        wykres.grupa_box.add(line);

        var lineGeometry = new THREE.Geometry();
        var vertArray = lineGeometry.vertices;
        vertArray.push(new THREE.Vector3(i,   0,wykres.sciana_z_poz*100));
        vertArray.push(new THREE.Vector3(i,1000,wykres.sciana_z_poz*100));
        lineGeometry.computeLineDistances();

        var lineMaterial = new THREE.LineBasicMaterial({color: colorBox2});
        var line = new THREE.Line(lineGeometry,lineMaterial);
        wykres.grupa_box.add(line);

        //xz
        var lineGeometry = new THREE.Geometry();
        var vertArray = lineGeometry.vertices;
        vertArray.push(new THREE.Vector3(   0,wykres.sciana_y_poz*100,i));
        vertArray.push(new THREE.Vector3(1020,wykres.sciana_y_poz*100,i));
        lineGeometry.computeLineDistances();

        var lstart=box_liczby_osie.e.start;
        var lkrok=box_liczby_osie.e.krok;
        var llp=i/100;

        if(lstart+llp*lkrok==0)
            var lineMaterial = new THREE.LineBasicMaterial({color: colorBox2,linewidth:3});
        else
            var lineMaterial = new THREE.LineBasicMaterial({color: colorBox2});

        var line = new THREE.Line(lineGeometry,lineMaterial);
        wykres.grupa_box.add(line);

        var lineGeometry = new THREE.Geometry();
        var vertArray = lineGeometry.vertices;
        vertArray.push(new THREE.Vector3(i,wykres.sciana_y_poz*100,   0));
        vertArray.push(new THREE.Vector3(i,wykres.sciana_y_poz*100,1020));
        lineGeometry.computeLineDistances();

        var lstart=box_liczby_osie.n.start;
        var lkrok=box_liczby_osie.n.krok;
        var llp=i/100;

        if(lstart+llp*lkrok==0)
            var lineMaterial = new THREE.LineBasicMaterial({color: colorBox2,linewidth:3});
        else
            var lineMaterial = new THREE.LineBasicMaterial({color: colorBox2});

        var line = new THREE.Line(lineGeometry,lineMaterial);
        wykres.grupa_box.add(line);

        //yz
        var lineGeometry = new THREE.Geometry();
        var vertArray = lineGeometry.vertices;
        vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,i,   0));
        vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,i,1020));
        lineGeometry.computeLineDistances();

        var lineMaterial = new THREE.LineBasicMaterial({color: colorBox2});
        var line = new THREE.Line(lineGeometry,lineMaterial);
        wykres.grupa_box.add(line);

        var lineGeometry = new THREE.Geometry();
        var vertArray = lineGeometry.vertices;
        vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,   0,i));
        vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,1000,i));
        lineGeometry.computeLineDistances();

        var lineMaterial = new THREE.LineBasicMaterial({color: colorBox2});
        var line = new THREE.Line(lineGeometry,lineMaterial);
        wykres.grupa_box.add(line);
    }
// główne ramki box
    //xy
    var lineGeometry = new THREE.Geometry();
    var vertArray = lineGeometry.vertices;

    //sciana_z_poz
    vertArray.push(new THREE.Vector3(   0,   0,wykres.sciana_z_poz*100));
    vertArray.push(new THREE.Vector3(1000,   0,wykres.sciana_z_poz*100));
    vertArray.push(new THREE.Vector3(1000,1000,wykres.sciana_z_poz*100));
    vertArray.push(new THREE.Vector3(   0,1000,wykres.sciana_z_poz*100));
    vertArray.push(new THREE.Vector3(   0,   0,wykres.sciana_z_poz*100));

    lineGeometry.computeLineDistances();
    var lineMaterial = new THREE.LineBasicMaterial({color: colorBox1});
    var line = new THREE.Line(lineGeometry,lineMaterial);
    wykres.grupa_box.add(line);

    //xz
    var lineGeometry = new THREE.Geometry();
    var vertArray = lineGeometry.vertices;
    vertArray.push(new THREE.Vector3(   0,wykres.sciana_y_poz*100,   0));
    vertArray.push(new THREE.Vector3(   0,wykres.sciana_y_poz*100,1000));
    vertArray.push(new THREE.Vector3(1000,wykres.sciana_y_poz*100,1000));
    vertArray.push(new THREE.Vector3(1000,wykres.sciana_y_poz*100,   0));
    vertArray.push(new THREE.Vector3(   0,wykres.sciana_y_poz*100,   0));

    lineGeometry.computeLineDistances();
    var lineMaterial = new THREE.LineBasicMaterial({color: colorBox1});
    var line = new THREE.Line(lineGeometry,lineMaterial);
    wykres.grupa_box.add(line);

    //yz
    var lineGeometry = new THREE.Geometry();
    var vertArray = lineGeometry.vertices;

    //sciana_x_poz
    vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,   0,   0));
    vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,   0,1000));
    vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,1000,1000));
    vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,1000,   0));
    vertArray.push(new THREE.Vector3(wykres.sciana_x_poz*100,   0,   0));

    lineGeometry.computeLineDistances();
    var lineMaterial = new THREE.LineBasicMaterial({color: colorBox1});
    var line = new THREE.Line(lineGeometry,lineMaterial);
    wykres.grupa_box.add(line);

    wykres.scene.add(wykres.grupa_box);
}//}}}
wykres.pre_update_liczby_osi_y=function(x)//{{{
{
    //liczby osi Y(t)
    //parametr 'x' określa kierunek tekstu
    var i;
    var str;

    if(x==1)
        wykres.grupa_liczby_y_pos=new THREE.Object3D();
    else if(x==-1)
        wykres.grupa_liczby_y_neg=new THREE.Object3D();
    else if(x==2) //ortho
        wykres.grupa_liczby_y_ortho=new THREE.Object3D();

    for(i=0;i<11;i++)
    {
        str=box_liczby_osie.t.start+i*box_liczby_osie.t.krok;
        // 3D text
        var materialFront = new THREE.MeshBasicMaterial( { color: 0x88aa88 } );
        var materialSide = new THREE.MeshBasicMaterial( { color: 0x88aa88} );
        var materialArray = [ materialFront, materialSide ];
        var textGeom = new THREE.TextGeometry(str,
            {
                size: 30, height: 0, curveSegments: 3,
                font: "optimer", weight: "normal", style: "normal",
                bevelThickness: 1, bevelSize: 1, bevelEnabled: false,
                material: 0, extrudeMaterial: 1
            });

        var textMaterial = new THREE.MeshFaceMaterial(materialArray);
        var textMesh = new THREE.Mesh(textGeom, textMaterial );

        textGeom.computeBoundingBox();
        var textWidth = textGeom.boundingBox.max.x - textGeom.boundingBox.min.x;

        if(x==1)
        {
            textMesh.position.set(wykres.sciana_x_poz*100,-i*100-10+1000,1020+textWidth);
            textMesh.rotation.y=Math.PI/2;
            wykres.grupa_liczby_y_pos.add(textMesh); //oś tvd
        }
        else if(x==-1)
        {
            textMesh.position.set(wykres.sciana_x_poz*100,-i*100-10+1000,1020);
            textMesh.rotation.y=-Math.PI/2;
            wykres.grupa_liczby_y_neg.add(textMesh);
        }
        else if(x==2) //ortho
        {
            textMesh.position.set(wykres.sciana_x_poz*100-textWidth-20,-i*100-10+1000,1020);
            textMesh.rotation.y=0;
            wykres.grupa_liczby_y_ortho.add(textMesh);
        }

    }

}//}}}
wykres.pre_update_liczby_osi_x=function(x)//{{{
{
    if(x==1)
        wykres.grupa_liczby_x_pos = new THREE.Object3D();
    else if(x==-1)
        wykres.grupa_liczby_x_neg = new THREE.Object3D();
    else if(x==2)
        wykres.grupa_liczby_x_ortho = new THREE.Object3D();

    var i;
    var str;
    //liczby osi X(n)

    for(i=0;i<11;i++)
    {
        str=box_liczby_osie.n.start+(i+0)*box_liczby_osie.n.krok;//od drugiej pozycji

        //3D text
        var materialFront = new THREE.MeshBasicMaterial({color: 0x8888aa });
        var materialSide = new THREE.MeshBasicMaterial({color: 0x8888aa});
        var materialArray = [ materialFront, materialSide ];
        var textGeom = new THREE.TextGeometry(str,
            {
                size: 30, height: 0, curveSegments: 3,
                font: "optimer", weight: "normal", style: "normal",
                bevelThickness: 1, bevelSize: 1, bevelEnabled: false,
                material: 0, extrudeMaterial: 1
            });

        var textMaterial = new THREE.MeshFaceMaterial(materialArray);
        var textMesh = new THREE.Mesh(textGeom, textMaterial );

        textGeom.computeBoundingBox();
        var textWidth = textGeom.boundingBox.max.x - textGeom.boundingBox.min.x;

        if(x==1)
        {
            textMesh.rotation.z=Math.PI/2;
            textMesh.rotation.x=-Math.PI/2;
            textMesh.position.set(i*100+10,wykres.sciana_y_poz*100,1020+textWidth);
            wykres.grupa_liczby_x_pos.add(textMesh);
        }
        else if(x==-1)
        {
            textMesh.rotation.z=-Math.PI/2;
            textMesh.rotation.x=-Math.PI/2;
            textMesh.position.set(i*100-15,wykres.sciana_y_poz*100,1020);
            wykres.grupa_liczby_x_neg.add(textMesh);
        }
        else if(x==2)
        {
            textMesh.rotation.z=-Math.PI/2;
            textMesh.rotation.x=0;
            textMesh.position.set(i*100-15,wykres.sciana_y_poz*100-20,1020);
            wykres.grupa_liczby_x_ortho.add(textMesh);
        }


    }

}//}}}
wykres.pre_update_liczby_osi_z=function(z)//{{{
{
    if(z==1)
        wykres.grupa_liczby_z_pos = new THREE.Object3D();
    else if(z==-1)
        wykres.grupa_liczby_z_neg = new THREE.Object3D();
    else if(z==2)
        wykres.grupa_liczby_z_ortho = new THREE.Object3D();

    var i;
    var str;
    //liczby osi Z(e)

    for(i=0;i<11;i++)
    {
        str=box_liczby_osie.e.start+i*box_liczby_osie.e.krok;

        var materialFront = new THREE.MeshBasicMaterial( { color: 0xaa4444 } );
        var materialSide = new THREE.MeshBasicMaterial({color: 0x8888aa});
        var materialArray = [materialFront, materialSide];

        var textGeom = new THREE.TextGeometry(str,
            {
                size: 30, height: 0, curveSegments: 3,
                font: "optimer", weight: "normal", style: "normal",
                bevelThickness: 1, bevelSize: 1, bevelEnabled: false,
                material: 0, extrudeMaterial: 1
            });

        var textMaterial = new THREE.MeshFaceMaterial(materialArray);
        var textMesh = new THREE.Mesh(textGeom,textMaterial);

        textGeom.computeBoundingBox();
        var textWidth = textGeom.boundingBox.max.x - textGeom.boundingBox.min.x;

        if(z==1)
        {
            textMesh.position.set(1020,wykres.sciana_y_poz*100,i*100+10);
            textMesh.rotation.x=-Math.PI/2;
            wykres.grupa_liczby_z_pos.add(textMesh);
        }
        else if(z==-1)
        {
            textMesh.position.set(1020+textWidth,wykres.sciana_y_poz,i*100-15);
            textMesh.rotation.x=-Math.PI/2;
            textMesh.rotation.z=Math.PI;
            wykres.grupa_liczby_z_neg.add(textMesh);
        }
        else if(z==2)
        {
            textMesh.position.set(1020+textWidth,wykres.sciana_y_poz-textWidth-20,i*100-15);
            textMesh.rotation.x=Math.PI/2;
            textMesh.rotation.y=Math.PI/2;
            wykres.grupa_liczby_z_ortho.add(textMesh);
        }
    }

    //wykres.scene.add(grupa_liczby_z);
}//}}}
wykres.pre_generuj_grupy_liczby_osi=function()//{{{
{
    wykres.pre_update_liczby_osi_x(1);
    wykres.pre_update_liczby_osi_x(-1);
    wykres.pre_update_liczby_osi_x(2);

    wykres.pre_update_liczby_osi_y(1);
    wykres.pre_update_liczby_osi_y(-1);
    wykres.pre_update_liczby_osi_y(2);

    wykres.pre_update_liczby_osi_z(1);
    wykres.pre_update_liczby_osi_z(-1);
    wykres.pre_update_liczby_osi_z(2);
}//}}}
wykres.update_liczby_osi_y=function()//{{{
{
    //liczby osi Y(t)
    var i;
    var str;

    wykres.grupa_liczby_y=new THREE.Object3D();

    for(i=0;i<11;i++)
    {
        str=box_liczby_osie.t.start+i*box_liczby_osie.t.krok;

        // 3D text
        var materialFront = new THREE.MeshBasicMaterial( { color: 0x88aa88 } );
        var materialSide = new THREE.MeshBasicMaterial( { color: 0x88aa88} );
        var materialArray = [ materialFront, materialSide ];
        var textGeom = new THREE.TextGeometry(str,
            {
                size: 30, height: 0, curveSegments: 3,
                font: "optimer", weight: "normal", style: "normal",
                bevelThickness: 1, bevelSize: 1, bevelEnabled: false,
                material: 0, extrudeMaterial: 1
            });

        // font: helvetiker, gentilis, droid sans, droid serif, optimer
        // weight: normal, bold

        var textMaterial = new THREE.MeshFaceMaterial(materialArray);
        var textMesh = new THREE.Mesh(textGeom, textMaterial );

        textGeom.computeBoundingBox();
        var textWidth = textGeom.boundingBox.max.x - textGeom.boundingBox.min.x;

        if(wykres.camera.position.x>0)
        {
            textMesh.position.set(0,-i*100-10+1000,1020+textWidth);
            textMesh.rotation.y=Math.PI/2;
        }
        else
        {
            textMesh.position.set(0,-i*100-10+1000,1020);
            textMesh.rotation.y=-Math.PI/2;
        }

        wykres.grupa_liczby_y.add(textMesh);
    }

    wykres.scene.add(wykres.grupa_liczby_y);
}//}}}
wykres.update_liczby_osi_x=function()//{{{
{
    wykres.grupa_liczby_x = new THREE.Object3D();
    var i;
    var str;
    //liczby osi X(n)

    for(i=0;i<10;i++)
    {
        str=box_liczby_osie.n.start+(i+1)*box_liczby_osie.n.krok;//od drugiej pozycji

        // 3D text
        var materialFront = new THREE.MeshBasicMaterial({color: 0x8888aa });
        var materialSide = new THREE.MeshBasicMaterial({color: 0x8888aa});
        //var materialSide = new THREE.MeshBasicMaterial( { color: 0xaa8888} );
        var materialArray = [ materialFront, materialSide ];
        var textGeom = new THREE.TextGeometry(str,
            {
                size: 30, height: 0, curveSegments: 3,
                font: "optimer", weight: "normal", style: "normal",
                bevelThickness: 1, bevelSize: 1, bevelEnabled: false,
                material: 0, extrudeMaterial: 1
            });

        // font: helvetiker, gentilis, droid sans, droid serif, optimer
        // weight: normal, bold

        var textMaterial = new THREE.MeshFaceMaterial(materialArray);
        var textMesh = new THREE.Mesh(textGeom, textMaterial );

        textGeom.computeBoundingBox();
        var textWidth = textGeom.boundingBox.max.x - textGeom.boundingBox.min.x;

        if(wykres.camera.position.x>0)
        {
            textMesh.rotation.z=Math.PI/2;
            textMesh.rotation.x=-Math.PI/2;
            textMesh.position.set(i*100+110,0,1020+textWidth);
        }
        else
        {
            textMesh.rotation.z=-Math.PI/2;
            textMesh.rotation.x=-Math.PI/2;
            textMesh.position.set(i*100+110,0,1020);
        }

        wykres.grupa_liczby_x.add(textMesh);
    }

    wykres.scene.add(wykres.grupa_liczby_x);
}//}}}
wykres.update_liczby_osi_z=function()//{{{
{
    wykres.grupa_liczby_z = new THREE.Object3D();
    var i;
    var str;
    //liczby osi Z(e)

    for(i=0;i<11;i++)
    {
        str=box_liczby_osie.e.start+i*box_liczby_osie.e.krok;

        var materialFront = new THREE.MeshBasicMaterial( { color: 0xaa4444 } );
        var materialSide = new THREE.MeshBasicMaterial({color: 0x8888aa});
        var materialArray = [materialFront, materialSide];

        var textGeom = new THREE.TextGeometry(str,
            {
                size: 30, height: 0, curveSegments: 3,
                font: "optimer", weight: "normal", style: "normal",
                bevelThickness: 1, bevelSize: 1, bevelEnabled: false,
                material: 0, extrudeMaterial: 1
            });

        var textMaterial = new THREE.MeshFaceMaterial(materialArray);
        var textMesh = new THREE.Mesh(textGeom,textMaterial);

        textGeom.computeBoundingBox();
        var textWidth = textGeom.boundingBox.max.x - textGeom.boundingBox.min.x;

        if(wykres.camera.position.z>0)
        {
            textMesh.position.set(1020,0,i*100+10);
            textMesh.rotation.x=-Math.PI/2;
        }
        else
        {
            textMesh.position.set(1020+textWidth,0,i*100+10);
            textMesh.rotation.x=-Math.PI/2;
            textMesh.rotation.z=Math.PI;
        }
        wykres.grupa_liczby_z.add(textMesh);
    }

    wykres.scene.add(wykres.grupa_liczby_z);
}//}}}
wykres.symbol_polnoc=function(x,y,z)//{{{
{
    var Z=new THREE.Vector3(x,y,z);
    var d=2;

    var A=new THREE.Vector3(164,0,96);
    var B=new THREE.Vector3(247,0,56);
    var C=new THREE.Vector3(164,0,18);
    var D=new THREE.Vector3(109,0,115);
    var E=new THREE.Vector3(109,0,0);
    var F=new THREE.Vector3(0,0,56);
    var N=new THREE.Vector3(240,0,80);

    A.divideScalar(d);
    B.divideScalar(d);
    C.divideScalar(d);
    D.divideScalar(d);
    E.divideScalar(d);
    F.divideScalar(d);
    N.divideScalar(d);

    A.add(Z);
    B.add(Z);
    C.add(Z);
    D.add(Z);
    E.add(Z);
    F.add(Z);
    N.add(Z);

//pionowa
    var lineGeometry = new THREE.Geometry();
    var vertArray = lineGeometry.vertices;
    vertArray.push(B);
    vertArray.push(F);
    lineGeometry.computeLineDistances();

    var lineMaterial = new THREE.LineBasicMaterial({color: wykres.colorSymbolPln,linewidth:2});
    var line = new THREE.Line(lineGeometry,lineMaterial);
    wykres.scene.add(line);

//pozioma
    var lineGeometry = new THREE.Geometry();
    var vertArray = lineGeometry.vertices;
    vertArray.push(D);
    vertArray.push(E);
    lineGeometry.computeLineDistances();

    var lineMaterial = new THREE.LineBasicMaterial({color: wykres.colorSymbolPln,linewidth:2});
    var line = new THREE.Line(lineGeometry,lineMaterial);
    wykres.scene.add(line);

//strzałka
    var lineGeometry = new THREE.Geometry();
    var vertArray = lineGeometry.vertices;
    vertArray.push(A);
    vertArray.push(B);
    vertArray.push(C);
    lineGeometry.computeLineDistances();

    var lineMaterial = new THREE.LineBasicMaterial({color: wykres.colorSymbolPln,linewidth:2});
    var line = new THREE.Line(lineGeometry,lineMaterial);
    wykres.scene.add(line);

    var materialFront = new THREE.MeshBasicMaterial( { color: 0x888888 } );
    var materialSide = new THREE.MeshBasicMaterial( { color: 0x888888} );
    var materialArray = [ materialFront, materialSide ];
    var textGeom = new THREE.TextGeometry('N',
        {
            size: 30, height: 0, curveSegments: 3,
            font: "optimer", weight: "bold", style: "normal",
            bevelThickness: 1, bevelSize: 1, bevelEnabled: false,
            material: 0, extrudeMaterial: 1
        });

    // font: helvetiker, gentilis, droid sans, droid serif, optimer
    // weight: normal, bold

    var textMaterial = new THREE.MeshFaceMaterial(materialArray);
    var textMesh = new THREE.Mesh(textGeom, textMaterial );

    textGeom.computeBoundingBox();
    var textWidth = textGeom.boundingBox.max.x - textGeom.boundingBox.min.x;

    textMesh.position.set(N.x,N.y,N.z);

    textMesh.rotation.x=-Math.PI/2;
    textMesh.rotation.z=-Math.PI/2;

    wykres.scene.add(textMesh);
}//}}}
wykres.makeTextSprite=function(message,parameters)//{{{
{
    if (parameters === undefined) parameters = {};

    var fontface = parameters.hasOwnProperty("fontface") ?
        parameters["fontface"] : "Arial";

    var fontsize = parameters.hasOwnProperty("fontsize") ?
        parameters["fontsize"] : 18;

    var borderThickness = parameters.hasOwnProperty("borderThickness") ?
        parameters["borderThickness"] : 4;

    var borderColor = parameters.hasOwnProperty("borderColor") ?
        parameters["borderColor"] : { r:0, g:0, b:0, a:1.0 };

    var backgroundColor = parameters.hasOwnProperty("backgroundColor") ?
        parameters["backgroundColor"] : { r:255, g:255, b:255, a:1.0 };

    var color=parameters['color'];

    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');

    canvas.width=1024;
    canvas.height=1024;

    // get size data (height depends only on font size)
    var metrics = context.measureText(message);
    var textWidth = metrics.width;

    // text color
    //context.fillStyle = "rgba(256, 0, 0, 1.0)";
    context.fillStyle = color;
    //context.textAlign="start";

//	context.fillText(message,borderThickness,fontsize+borderThickness);
    context.font = fontsize + "px " + fontface;
    context.fillText(message,canvas.width/2,canvas.height/2);

    var texture = new THREE.Texture(canvas)
    texture.needsUpdate = true;

    var spriteMaterial = new THREE.SpriteMaterial({map: texture});
    var sprite = new THREE.Sprite(spriteMaterial);
    sprite.scale.set(800,800,1.0);
    return sprite;
}//}}}
wykres.rzutuj_wykres=function(flg)//{{{
{
    if(flg)
    {
        var i;
        var abox=[];
        var color=0x88aaaa;
        var bbox;

        wykres.scene.remove(wykres.grupa_rzut_x);
        wykres.scene.remove(wykres.grupa_rzut_y);
        wykres.scene.remove(wykres.grupa_rzut_z);

        wykres.grupa_rzut_x = new THREE.Object3D();
        wykres.grupa_rzut_y = new THREE.Object3D();
        wykres.grupa_rzut_z = new THREE.Object3D();

        for(j=0;j<mbox.length;j++)
        {
            if(mdata_wybrane[j]==1)
            {
                var line;
                bbox=mbox[j];

                abox=[];

                for(i=0;i<bbox.length;i++)
                    abox.push({'x':wykres.sciana_x_poz*100,'y':bbox[i].y,'z':bbox[i].z});

                var geometry=new THREE.Geometry();
                geometry.vertices=abox;

                var material = new THREE.LineBasicMaterial({
                    color: color, linewidth:2});

                line = new THREE.Line(geometry,material);

                wykres.grupa_rzut_x.add(line);

                abox=[];

                for(i=0;i<bbox.length;i++)
                    abox.push({'x':bbox[i].x,'y':wykres.sciana_y_poz*100,'z':bbox[i].z});

                var geometry=new THREE.Geometry();
                geometry.vertices=abox;

                var material = new THREE.LineBasicMaterial({
                    color: color, linewidth:2});

                var line = new THREE.Line(geometry,material);

                wykres.grupa_rzut_y.add(line);

                abox=[];

                for(i=0;i<bbox.length;i++)
                    abox.push({'x':bbox[i].x,'y':bbox[i].y,'z':wykres.sciana_z_poz*100});

                var geometry=new THREE.Geometry();
                geometry.vertices=abox;

                var material = new THREE.LineBasicMaterial({
                    color: color, linewidth:2});

                var line = new THREE.Line(geometry,material);

                wykres.grupa_rzut_z.add(line);

                line={};
            }

            if(wykres.menu_2_1==1)
                wykres.scene.add(wykres.grupa_rzut_x);

            if(wykres.menu_2_3==1)
                wykres.scene.add(wykres.grupa_rzut_y);

            if(wykres.menu_2_2==1)
                wykres.scene.add(wykres.grupa_rzut_z);

        }

    }
    else //{{{
    {
        var i;
        var abox=[];
        var color=0x88aaaa;

        wykres.scene.remove(wykres.grupa_rzut_x);
        wykres.scene.remove(wykres.grupa_rzut_y);
        wykres.scene.remove(wykres.grupa_rzut_z);

        wykres.grupa_rzut_x = new THREE.Object3D();
        wykres.grupa_rzut_y = new THREE.Object3D();
        wykres.grupa_rzut_z = new THREE.Object3D();

        for(i=0;i<box.length;i++)
            abox.push({'x':wykres.sciana_x_poz*100,'y':box[i].y,'z':box[i].z});

        var geometry=new THREE.Geometry();
        geometry.vertices=abox;

        var material = new THREE.LineBasicMaterial({
            color: color, linewidth:2});

        var line = new THREE.Line(geometry,material);

        wykres.grupa_rzut_x.add(line);

        abox=[];

        for(i=0;i<box.length;i++)
            abox.push({'x':box[i].x,'y':wykres.sciana_y_poz*100,'z':box[i].z});

        var geometry=new THREE.Geometry();
        geometry.vertices=abox;

        var material = new THREE.LineBasicMaterial({
            color: color, linewidth:2});

        var line = new THREE.Line(geometry,material);

        wykres.grupa_rzut_y.add(line);

        abox=[];

        for(i=0;i<box.length;i++)
            abox.push({'x':box[i].x,'y':box[i].y,'z':wykres.sciana_z_poz*100});

        var geometry=new THREE.Geometry();
        geometry.vertices=abox;

        var material = new THREE.LineBasicMaterial({
            color: color, linewidth:2});

        var line = new THREE.Line(geometry,material);

        wykres.grupa_rzut_z.add(line);

        if(wykres.menu_2_1==1)
            wykres.scene.add(wykres.grupa_rzut_x);

        if(wykres.menu_2_3==1)
            wykres.scene.add(wykres.grupa_rzut_y);

        if(wykres.menu_2_2==1)
            wykres.scene.add(wykres.grupa_rzut_z);
    }//}}}
}//}}}
wykres.rzutuj_punkty=function(flg)//{{{
{
    //global box_rzut
    //global scene

    //global grupa_na_scianach_punkty
    //global grupa_punkty_charakterystyczne
    //global grupa_punkty_charakterystyczne_opis
    if(flg)
    {
        wykres.scene.remove(wykres.grupa_na_scianach_punkty);
        wykres.scene.remove(wykres.grupa_punkty_charakterystyczne);
        wykres.scene.remove(wykres.grupa_punkty_charakterystyczne_opis);

        wykres.grupa_na_scianach_punkty = new THREE.Object3D();
        wykres.grupa_punkty_charakterystyczne = new THREE.Object3D();
        wykres.grupa_punkty_charakterystyczne_opis = new THREE.Object3D();

        for(j=0;j<mbox.length;j++)
        {
            if(mdata_wybrane[j]==1)
            {
                var i;
                var sphereGeom = new THREE.SphereGeometry(4,32,16);

                for(i=0;i<mbox_rzut[j].length;i++)
                {
                    //rzutuj na ściany
                    //rzutuj punkt xy
                    var material = new THREE.LineDashedMaterial({color:0x666666,dashSize:4,gapSize:2,scale:0.4});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(mbox_rzut[j][i].x,mbox_rzut[j][i].y,mbox_rzut[j][i].z)); //punkt rzutowany na wykresie
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x,mbox[j][mbox_rzut[j][i].pidbox].y,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);

                    var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x+10,mbox[j][mbox_rzut[j][i].pidbox].y,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x-10,mbox[j][mbox_rzut[j][i].pidbox].y,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);

                    var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x,mbox[j][mbox_rzut[j][i].pidbox].y-10,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x,mbox[j][mbox_rzut[j][i].pidbox].y+10,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);

                    //rzutuj punkt yz
                    var material = new THREE.LineDashedMaterial({color:0x666666,dashSize:4,gapSize:2,scale:0.4});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(mbox_rzut[j][i].x,mbox_rzut[j][i].y,mbox_rzut[j][i].z)); //punkt rzutowany na wykresie
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x,wykres.sciana_y_poz*100,mbox[j][mbox_rzut[j][i].pidbox].z)); //punkt rzutowany na yz
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);

                    var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x-10,wykres.sciana_y_poz*100,mbox[j][mbox_rzut[j][i].pidbox].z)); //punkt rzutowany na yz
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x+10,wykres.sciana_y_poz*100,mbox[j][mbox_rzut[j][i].pidbox].z)); //punkt rzutowany na yz
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);

                    var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x,wykres.sciana_y_poz*100,mbox[j][mbox_rzut[j][i].pidbox].z-10)); //punkt rzutowany na yz
                    geometry.vertices.push(new THREE.Vector3(mbox[j][mbox_rzut[j][i].pidbox].x,wykres.sciana_y_poz*100,mbox[j][mbox_rzut[j][i].pidbox].z+10)); //punkt rzutowany na yz
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);

                    //rzutuj punkt xz
                    var material = new THREE.LineDashedMaterial({color:0x666666,dashSize:4,gapSize:2,scale:0.4});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(mbox_rzut[j][i].x,mbox_rzut[j][i].y,mbox_rzut[j][i].z)); //punkt rzutowany na wykresie
                    geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,mbox[j][mbox_rzut[j][i].pidbox].y,mbox[j][mbox_rzut[j][i].pidbox].z)); //punkt rzutowany na xz
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);

                    var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,mbox[j][mbox_rzut[j][i].pidbox].y-10,mbox[j][mbox_rzut[j][i].pidbox].z)); //punkt rzutowany na xz
                    geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,mbox[j][mbox_rzut[j][i].pidbox].y+10,mbox[j][mbox_rzut[j][i].pidbox].z)); //punkt rzutowany na xz
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);

                    var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,mbox[j][mbox_rzut[j][i].pidbox].y,mbox[j][mbox_rzut[j][i].pidbox].z-10)); //punkt rzutowany na xz
                    geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,mbox[j][mbox_rzut[j][i].pidbox].y,mbox[j][mbox_rzut[j][i].pidbox].z+10)); //punkt rzutowany na xz
                    geometry.computeLineDistances();
                    var line = new THREE.Line(geometry,material);
                    wykres.grupa_na_scianach_punkty.add(line);


                    //rysuj punkt
                    var sphereMaterial = new THREE.MeshBasicMaterial({color: 0xcc0000});
                    var sphere = new THREE.Mesh(sphereGeom.clone(),sphereMaterial);
                    sphere.position.set(mbox_rzut[j][i].x,mbox_rzut[j][i].y,mbox_rzut[j][i].z);
                    wykres.grupa_punkty_charakterystyczne.add(sphere);

                    //wypisz info rzutowanego punktu
                    var spritey = wykres.makeTextSprite(mbox_rzut[j][i].info, {
                        fontsize: 24,
                        color: 'grey',

                        borderColor: {r:0, g:0, b:0, a:1.0},
                        backgroundColor: {r:255, g:100, b:100, a:0.8}});

                    //				spritey.position.set(mbox_rzut[j][i].x+20,mbox_rzut[j][i].y+10,mbox_rzut[j][i].z);
                    spritey.position.set(mbox_rzut[j][i].x+20,mbox_rzut[j][i].y+10+20*j,mbox_rzut[j][i].z);

                    wykres.grupa_punkty_charakterystyczne_opis.add(spritey);
                }

                if(wykres.menu_1==1)
                    wykres.scene.add(wykres.grupa_na_scianach_punkty);

                if(wykres.menu_1==1)
                    wykres.scene.add(wykres.grupa_punkty_charakterystyczne);

                if(wykres.menu_4==1)
                    wykres.scene.add(wykres.grupa_punkty_charakterystyczne_opis);

            }
        }
    }
    else
    {
        var i;
        var sphereGeom = new THREE.SphereGeometry(4,32,16);

        wykres.scene.remove(wykres.grupa_na_scianach_punkty);
        wykres.scene.remove(wykres.grupa_punkty_charakterystyczne);
        wykres.scene.remove(wykres.grupa_punkty_charakterystyczne_opis);

        wykres.grupa_na_scianach_punkty = new THREE.Object3D();
        wykres.grupa_punkty_charakterystyczne = new THREE.Object3D();
        wykres.grupa_punkty_charakterystyczne_opis = new THREE.Object3D();

        for(i=0;i<box_rzut.length;i++)
        {
            //rzutuj na ściany
            //rzutuj punkt xy
            var material = new THREE.LineDashedMaterial({color:0x666666,dashSize:4,gapSize:2,scale:0.4});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(box_rzut[i].x,box_rzut[i].y,box_rzut[i].z)); //punkt rzutowany na wykresie
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x,box[box_rzut[i].pidbox].y,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x+10,box[box_rzut[i].pidbox].y,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x-10,box[box_rzut[i].pidbox].y,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x,box[box_rzut[i].pidbox].y-10,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x,box[box_rzut[i].pidbox].y+10,wykres.sciana_z_poz*100)); //punkt rzutowany na xy
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            //rzutuj punkt yz
            var material = new THREE.LineDashedMaterial({color:0x666666,dashSize:4,gapSize:2,scale:0.4});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(box_rzut[i].x,box_rzut[i].y,box_rzut[i].z)); //punkt rzutowany na wykresie
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x,wykres.sciana_y_poz*100,box[box_rzut[i].pidbox].z)); //punkt rzutowany na yz
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x-10,wykres.sciana_y_poz*100,box[box_rzut[i].pidbox].z)); //punkt rzutowany na yz
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x+10,wykres.sciana_y_poz*100,box[box_rzut[i].pidbox].z)); //punkt rzutowany na yz
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x,wykres.sciana_y_poz*100,box[box_rzut[i].pidbox].z-10)); //punkt rzutowany na yz
            geometry.vertices.push(new THREE.Vector3(box[box_rzut[i].pidbox].x,wykres.sciana_y_poz*100,box[box_rzut[i].pidbox].z+10)); //punkt rzutowany na yz
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            //rzutuj punkt xz
            var material = new THREE.LineDashedMaterial({color:0x666666,dashSize:4,gapSize:2,scale:0.4});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(box_rzut[i].x,box_rzut[i].y,box_rzut[i].z)); //punkt rzutowany na wykresie
            geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,box[box_rzut[i].pidbox].y,box[box_rzut[i].pidbox].z)); //punkt rzutowany na xz
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,box[box_rzut[i].pidbox].y-10,box[box_rzut[i].pidbox].z)); //punkt rzutowany na xz
            geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,box[box_rzut[i].pidbox].y+10,box[box_rzut[i].pidbox].z)); //punkt rzutowany na xz
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            var material = new THREE.LineBasicMaterial({ color: 0x666666, linewidth: 2, fog:true});
            var geometry = new THREE.Geometry();
            geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,box[box_rzut[i].pidbox].y,box[box_rzut[i].pidbox].z-10)); //punkt rzutowany na xz
            geometry.vertices.push(new THREE.Vector3(wykres.sciana_x_poz*100,box[box_rzut[i].pidbox].y,box[box_rzut[i].pidbox].z+10)); //punkt rzutowany na xz
            geometry.computeLineDistances();
            var line = new THREE.Line(geometry,material);
            wykres.grupa_na_scianach_punkty.add(line);

            //rysuj punkt
            var sphereMaterial = new THREE.MeshBasicMaterial({color: 0xcc0000});
            var sphere = new THREE.Mesh(sphereGeom.clone(),sphereMaterial);
            sphere.position.set(box_rzut[i].x,box_rzut[i].y,box_rzut[i].z);
            wykres.grupa_punkty_charakterystyczne.add(sphere);

            //wypisz info rzutowanego punktu
            var spritey = wykres.makeTextSprite(box_rzut[i].info, {
                fontsize: 24,
                color: 'grey',

                borderColor: {r:0, g:0, b:0, a:1.0},
                backgroundColor: {r:255, g:100, b:100, a:0.8}});

            spritey.position.set(box_rzut[i].x+20,box_rzut[i].y+10,box_rzut[i].z);

            wykres.grupa_punkty_charakterystyczne_opis.add(spritey);
        }

        if(wykres.menu_1==1)
            wykres.scene.add(wykres.grupa_na_scianach_punkty);

        if(wykres.menu_1==1)
            wykres.scene.add(wykres.grupa_punkty_charakterystyczne);

        if(wykres.menu_4==1)
        {
            wykres.scene.add(wykres.grupa_punkty_charakterystyczne_opis);
        }
    }
}//}}}	
wykres.dodaj_wykres=function(flg)//{{{
{
    if(flg)
    {
        wykres.scene.remove(wykres.grupa_wykres);
        wykres.grupa_wykres = new THREE.Object3D();

        var j;

        for(j=0;j<mbox.length;j++)
        {
            if(mdata_wybrane[j]==1)
            {
                var geometry = new THREE.Geometry();
                geometry.vertices=mbox[j];

                var material = new THREE.LineBasicMaterial({
                    color: mdata_c[j], linewidth: 3, fog:true});

                var line = new THREE.Line(geometry,material);

                //wykres.scene.add(line);
                wykres.grupa_wykres.add(line);
            }
        }

        wykres.scene.add(wykres.grupa_wykres);

    }
    else
    {
        var geometry=new THREE.Geometry();
        geometry.vertices=box;

        var material = new THREE.LineBasicMaterial({
            color: 0x6699FF, linewidth: 3, fog:true});
        var line = new THREE.Line(geometry,material);

        wykres.scene.add(line);
    }
}//}}}
wykres.animate=function()//{{{ 
{
    requestAnimationFrame(wykres.animate);
    wykres.controls.update();
}//}}}
wykres.init=function()//{{{
{
    // set the scene size
    var container_width=1200;
    var container_height=800;

    var aspectRatio=container_width/container_height;

    if(wykres.menu_camera==1)//PerspectiveCamera
    {
        wykres.cameraP = new THREE.PerspectiveCamera(75,container_width/container_height,0.1,10000);

        wykres.camera=wykres.cameraP;

        wykres.camera.position.x = 1455;
        wykres.camera.position.y = 1255;
        wykres.camera.position.z = 1255;
    }

    if(wykres.menu_camera==2)//OrthographicCamera
    {
        var viewSize=2000;
        wykres.cameraO = new THREE.OrthographicCamera(
            -aspectRatio*viewSize/2, aspectRatio*viewSize/2,
            viewSize/2, -viewSize/2, -2000,2000);

        wykres.camera=wykres.cameraO;

        //camera.rotation.order='YXZ';
        wykres.camera.position.x=10;
        wykres.camera.position.y=10;
        wykres.camera.position.z=10;
    }

    wykres.scene = new THREE.Scene();

    wykres.generuj_grupa_box();

    wykres.symbol_polnoc(500,0,1200);

    wykres.pre_generuj_grupy_liczby_osi();

    //początkowe przypisanie grup do sceny

    wykres.scene.add(wykres.grupa_liczby_y_pos);
    wykres.scene.add(wykres.grupa_liczby_x_pos);
    wykres.scene.add(wykres.grupa_liczby_z_pos);

    //rysowanie wykresu wykorzystując punkty w tablicy box

    wykres.dodaj_wykres(1);//mbox(1)

    wykres.opis_otworu(1);
    wykres.rzutuj_punkty(1);
    wykres.rzutuj_wykres(1);//^

    renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});

    //renderer.setSize(window.innerWidth,window.innerHeight);
    renderer.setSize(container_width,container_height);

    container=document.getElementById('wykres_3dpath');

    container.insertBefore(renderer.domElement, container.childNodes[0]);

    container.style.width=container_width+'px';

    wykres.controls = new THREE.OrbitControls(wykres.camera,container);

    wykres.controls.addEventListener('change',wykres.render);

    window.addEventListener('resize',wykres.onWindowResize,false);

    wykres.animate();
}//}}}
wykres.modelLoadedCallback=function(geometry)//{{{ 
{
    mesh = new THREE.Mesh(geometry, material);
    group.add(mesh);
    wykres.scene.add(group);
}//}}}
wykres.onWindowResize=function()//{{{
{
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();

    renderer.setSize(window.innerWidth, window.innerHeight);
    wykres.render();
}//}}}
wykres.render=function()//{{{  
{
    wykres.orientuj_napisy_osi_y();
    wykres.orientuj_napisy_osi_x();
    wykres.orientuj_napisy_osi_z();

    renderer.render(wykres.scene,wykres.camera);
}//}}}
wykres.reset=function()//{{{ 
{
    container.removeChild(container.childNodes[0]);
}//}}}
wykres.nastapila_zmiana_widocznosci_trajektorii=function()//{{{
{
    wykres.rzutuj_wykres(1);
    wykres.rzutuj_punkty(1);
    wykres.dodaj_wykres(1);
    wykres.opis_otworu(1);
    wykres.render();
}//}}}

wykres.set_menu();
wykres.init();
wykres.render();

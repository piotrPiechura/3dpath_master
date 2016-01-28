$(function(){
	$('#slideN').change(function(){//{{{
		wykres.sciana_x_poz=this.value;

		wykres.rzutuj_punkty(1);	
		wykres.rzutuj_wykres(1);
		wykres.orientuj_napisy_osi_y();
		wykres.nastapila_zmiana_sciana_xyz();
	});//}}}
	$('#slideE').change(function(){//{{{
		wykres.sciana_z_poz=this.value;

		wykres.rzutuj_punkty(1);	
		wykres.rzutuj_wykres(1);
		wykres.nastapila_zmiana_sciana_xyz();
	});//}}}
	$('#slideT').change(function(){//{{{
		wykres.sciana_y_poz=this.value;

		wykres.rzutuj_punkty(1);	
		wykres.rzutuj_wykres(1);
		wykres.nastapila_zmiana_sciana_xyz();
	});//}}}
	$('#menu_1').click(function(){//{{{
		wykres.menu_1=(wykres.menu_1==1)?0:1;
		wykres.set_menu();

		//punkty charakterystyczne i linie rzutujące	

		if(wykres.menu_1==0)
		{
			wykres.scene.remove(wykres.grupa_punkty_charakterystyczne);
			wykres.scene.remove(wykres.grupa_na_scianach_punkty);
		}
		else
		{		
			wykres.scene.add(wykres.grupa_punkty_charakterystyczne);
			wykres.scene.add(wykres.grupa_na_scianach_punkty);
		}

		wykres.render();
		this.blur();
	});//}}}
	$('#menu_2').click(function(){//{{{
		wykres.menu_2=(wykres.menu_2==1)?0:1;

		//(krzyżyki na ścianach, rzuty na poszczególne ściany)
		if(wykres.menu_2==0)
		{
			wykres.menu_2_1=0;//north
			wykres.menu_2_2=0;//east
			wykres.menu_2_3=0;//tvd

			wykres.scene.remove(wykres.grupa_rzut_x);
			wykres.scene.remove(wykres.grupa_rzut_y);
			wykres.scene.remove(wykres.grupa_rzut_z);
		}
		else
		{
			wykres.menu_2_1=1;//north
			wykres.menu_2_2=1;//east
			wykres.menu_2_3=1;//tvd

			wykres.scene.add(wykres.grupa_rzut_x);
			wykres.scene.add(wykres.grupa_rzut_y);
			wykres.scene.add(wykres.grupa_rzut_z);
		}

		wykres.set_menu();
		wykres.render();
		this.blur();
	});//}}}
	$('#menu_2_1').click(function(){//{{{
		wykres.menu_2_1=(wykres.menu_2_1==1)?0:1;

		if(wykres.menu_2_1==0)
			wykres.scene.remove(wykres.grupa_rzut_x);
		else
			wykres.scene.add(wykres.grupa_rzut_x);

		wykres.set_menu();
		wykres.render();
	});//}}}
	$('#menu_2_2').click(function(){//{{{
		wykres.menu_2_2=(wykres.menu_2_2==1)?0:1;

		if(wykres.menu_2_2==0)
			wykres.scene.remove(wykres.grupa_rzut_z);
		else
			wykres.scene.add(wykres.grupa_rzut_z);

		wykres.set_menu();
		wykres.render();
		this.blur();
	});//}}}
	$('#menu_2_3').click(function(){//{{{
		wykres.menu_2_3=(wykres.menu_2_3==1)?0:1;

		if(wykres.menu_2_3==0)
			wykres.scene.remove(wykres.grupa_rzut_y);
		else
			wykres.scene.add(wykres.grupa_rzut_y);

		wykres.set_menu();
		wykres.render();
		this.blur();
	});//}}}
	$('#menu_3').click(function(){//{{{
		wykres.menu_3=(wykres.menu_3==1)?0:1;
		wykres.set_menu();

		if(wykres.menu_3==0)
			wykres.scene.remove(wykres.grupa_opis_otworu);
		else
			wykres.scene.add(wykres.grupa_opis_otworu);

		wykres.render();
		this.blur();
	});//}}}
	$('#menu_4').click(function(){//{{{
		wykres.menu_4=(wykres.menu_4==1)?0:1;
		wykres.set_menu();

		if(wykres.menu_4==0)
			wykres.scene.remove(wykres.grupa_punkty_charakterystyczne_opis);
		else
			wykres.scene.add(wykres.grupa_punkty_charakterystyczne_opis);

		wykres.render();
		this.blur();
	});//}}}
	$('#cameraP').click(function(){//{{{
		wykres.menu_camera=1;
		wykres.set_menu();
		wykres.reset();

		wykres.init(); 
		wykres.render(); 
		this.blur();
	});//}}}
	$('#cameraO').click(function(){//{{{
		wykres.menu_camera=2;
		wykres.set_menu();
		wykres.reset();

		wykres.init(); 
		wykres.render(); 
		this.blur();
	});//}}}
	$('#ortho_east').click(function(){//{{{
		if(wykres.menu_camera==2)
		{
			wykres.scene.rotation.y=0;//sr
			wykres.camera.position.x = 10;
			wykres.camera.position.y = 0;
			wykres.camera.position.z = 0;
			wykres.controls.target.set(0,0,0);
			wykres.render(); 
			this.blur();
		}
	});//}}}
	$('#ortho_north').click(function(){ //{{{
		if(wykres.menu_camera==2)
		{
			wykres.scene.rotation.y=0;//sr
			wykres.camera.position.x = 0;
			wykres.camera.position.y = 0;
			wykres.camera.position.z = 10;
			wykres.controls.target.set(0,0,0);
			wykres.render(); 
			this.blur();
		}
	});//}}}
	$('#ortho_tvd').click(function(){//{{{
		if(wykres.menu_camera==2)
		{
			wykres.scene.rotation.y=Math.PI/2;//sr
			wykres.camera.position.set(0,10,0);
			wykres.controls.target.set(0,0,0);

			wykres.render();
			this.blur();
		}
	});//}}}
});

function cc1()//{{{
{
	var x;
	var s; 

	x=$(this).attr('value');
	s=($(this).is(':checked'))?1:0;
	mdata_wybrane[x]=s;

	wykres.nastapila_zmiana_widocznosci_trajektorii();
}//}}}
$(document).on('click','.trajektoria_item',cc1);	

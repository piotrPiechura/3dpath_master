//var data=[];
/*
var mdata=[]; 
var mdata_wybrane=[];

var mdata_pr=[];
var mdata_o=[];
var mdata_c=[];

var imdata=2;//ilość trajektorii 

mdata[0]=[]; 
mdata[1]=[];

mdata_wybrane[0]=1;
mdata_wybrane[1]=1;

//East[m];North[m];TVD[m];Dwernik-7A //{{{
mdata[0][0]=[0.00,0.00,0.00]; 
mdata[0][1]=[0.00,0.00,10.00];
mdata[0][2]=[0.00,0.00,20.00];
mdata[0][3]=[0.00,0.00,30.00];
mdata[0][4]=[0.00,0.00,40.00];
mdata[0][5]=[0.00,0.00,50.00];
mdata[0][6]=[0.00,0.00,60.00];
mdata[0][7]=[0.00,0.00,70.00];
mdata[0][8]=[0.00,0.00,80.00];
mdata[0][9]=[0.00,0.00,90.00];
mdata[0][10]=[0.00,0.00,100.00];
mdata[0][11]=[0.00,0.00,110.00];
mdata[0][12]=[0.00,0.00,120.00];
mdata[0][13]=[0.00,0.00,130.00];
mdata[0][14]=[0.00,0.00,140.00];
mdata[0][15]=[0.00,0.00,150.00];
mdata[0][16]=[0.01,-0.04,160.00];
mdata[0][17]=[0.05,-0.15,170.00];
mdata[0][18]=[0.10,-0.33,180.00];
mdata[0][19]=[0.19,-0.58,189.99];
mdata[0][20]=[0.29,-0.91,199.99];
mdata[0][21]=[0.42,-1.31,209.98];
mdata[0][22]=[0.57,-1.78,219.97];
mdata[0][23]=[0.74,-2.33,229.95];
mdata[0][24]=[0.94,-2.94,239.93];
mdata[0][25]=[1.16,-3.63,249.90];
mdata[0][26]=[1.41,-4.40,259.87];
mdata[0][27]=[1.67,-5.23,269.83];
mdata[0][28]=[1.97,-6.14,279.79];
mdata[0][29]=[2.28,-7.12,289.73];
mdata[0][30]=[2.62,-8.17,299.67];
mdata[0][31]=[2.98,-9.30,309.60];
mdata[0][32]=[3.36,-10.49,319.52];
mdata[0][33]=[3.77,-11.76,329.43];
mdata[0][34]=[4.19,-13.10,339.33];
mdata[0][35]=[4.65,-14.52,349.22];
mdata[0][36]=[5.12,-16.00,359.10];
mdata[0][37]=[5.62,-17.56,368.97];
mdata[0][38]=[6.14,-19.19,378.82];
mdata[0][39]=[6.69,-20.89,388.66];
mdata[0][40]=[7.25,-22.66,398.48];
mdata[0][41]=[7.84,-24.50,408.30];
mdata[0][42]=[8.46,-26.41,418.09];
mdata[0][43]=[9.09,-28.40,427.87];
mdata[0][44]=[9.75,-30.46,437.64];
mdata[0][45]=[10.43,-32.58,447.38];
mdata[0][46]=[11.13,-34.78,457.11];
mdata[0][47]=[11.86,-37.05,466.83];
mdata[0][48]=[12.61,-39.39,476.52];
mdata[0][49]=[13.38,-41.80,486.19];
mdata[0][50]=[14.17,-44.28,495.85];
mdata[0][51]=[14.99,-46.83,505.48];
mdata[0][52]=[15.83,-49.45,515.10];
mdata[0][53]=[16.69,-52.14,524.69];
mdata[0][54]=[17.57,-54.90,534.26];
mdata[0][55]=[18.48,-57.73,543.81];
mdata[0][56]=[19.41,-60.63,553.34];
mdata[0][57]=[20.36,-63.59,562.84];
mdata[0][58]=[21.33,-66.63,572.32];
mdata[0][59]=[22.32,-69.74,581.77];
mdata[0][60]=[23.34,-72.91,591.20];
mdata[0][61]=[24.38,-76.15,600.60];
mdata[0][62]=[25.44,-79.46,609.98];
mdata[0][63]=[26.52,-82.84,619.33];
mdata[0][64]=[27.62,-86.29,628.65];
mdata[0][65]=[28.75,-89.80,637.94];
mdata[0][66]=[29.89,-93.39,647.21];
mdata[0][67]=[31.06,-97.04,656.44];
mdata[0][68]=[32.25,-100.75,665.65];
mdata[0][69]=[33.46,-104.54,674.83];
mdata[0][70]=[34.69,-108.39,683.97];
mdata[0][71]=[35.95,-112.30,693.09];
mdata[0][72]=[37.22,-116.28,702.17];
mdata[0][73]=[38.52,-120.33,711.23];
mdata[0][74]=[39.84,-124.45,720.24];
mdata[0][75]=[41.17,-128.63,729.23];
mdata[0][76]=[42.53,-132.87,738.18];
mdata[0][77]=[43.91,-137.18,747.10];
mdata[0][78]=[45.31,-141.56,755.98];
mdata[0][79]=[46.73,-146.00,764.83];
mdata[0][80]=[48.18,-150.50,773.64];
mdata[0][81]=[49.64,-155.07,782.42];
mdata[0][82]=[51.12,-159.70,791.15];
mdata[0][83]=[52.62,-164.39,799.85];
mdata[0][84]=[54.14,-169.15,808.52];
mdata[0][85]=[55.69,-173.97,817.14];
mdata[0][86]=[57.25,-178.85,825.73];
mdata[0][87]=[58.83,-183.80,834.27];
mdata[0][88]=[60.44,-188.80,842.78];
mdata[0][89]=[62.06,-193.87,851.25];
mdata[0][90]=[63.70,-199.00,859.67];
mdata[0][91]=[65.36,-204.20,868.06];
mdata[0][92]=[67.04,-209.45,876.40];
mdata[0][93]=[68.75,-214.76,884.70];
mdata[0][94]=[70.47,-220.13,892.95];
mdata[0][95]=[72.20,-225.57,901.17];
mdata[0][96]=[72.20,-225.57,901.17];//}}}
//East[m];North[m];TVD[m]7K//{{{
mdata[1][0]=[0.00,0.00,0.00];
mdata[1][1]=[0.00,0.00,10.00];
mdata[1][2]=[0.00,0.00,20.00];
mdata[1][3]=[0.00,0.00,30.00];
mdata[1][4]=[0.00,0.00,40.00];
mdata[1][5]=[0.00,0.00,50.00];
mdata[1][6]=[0.00,0.00,60.00];
mdata[1][7]=[0.00,0.00,70.00];
mdata[1][8]=[0.00,0.00,80.00];
mdata[1][9]=[0.00,0.00,90.00];
mdata[1][10]=[0.00,0.00,100.00];
mdata[1][11]=[0.00,0.00,110.00];
mdata[1][12]=[0.00,0.00,120.00];
mdata[1][13]=[0.00,0.00,130.00];
mdata[1][14]=[0.00,0.00,140.00];
mdata[1][15]=[0.00,0.00,150.00];
mdata[1][16]=[0.00,0.00,160.00];
mdata[1][17]=[0.00,0.00,170.00];
mdata[1][18]=[0.00,0.00,180.00];
mdata[1][19]=[0.00,0.00,190.00];
mdata[1][20]=[0.00,0.00,200.00];
mdata[1][21]=[0.00,0.00,210.00];
mdata[1][22]=[0.00,0.00,220.00];
mdata[1][23]=[0.00,0.00,230.00];
mdata[1][24]=[0.00,0.00,240.00];
mdata[1][25]=[0.00,0.00,250.00];
mdata[1][26]=[0.00,0.00,260.00];
mdata[1][27]=[0.00,0.00,270.00];
mdata[1][28]=[0.00,0.00,280.00];
mdata[1][29]=[0.00,0.00,290.00];
mdata[1][30]=[0.00,0.00,300.00];
mdata[1][31]=[0.00,0.00,310.00];
mdata[1][32]=[0.00,0.00,320.00];
mdata[1][33]=[0.00,0.00,330.00];
mdata[1][34]=[0.00,0.00,340.00];
mdata[1][35]=[0.00,0.00,350.00];
mdata[1][36]=[0.00,0.00,360.00];
mdata[1][37]=[0.00,0.00,370.00];
mdata[1][38]=[0.00,0.00,379.38];
mdata[1][39]=[0.00,0.00,380.00];
mdata[1][40]=[0.03,-0.09,390.00];
mdata[1][41]=[0.11,-0.35,400.00];
mdata[1][42]=[0.25,-0.77,409.99];
mdata[1][43]=[0.43,-1.35,419.97];
mdata[1][44]=[0.67,-2.09,429.94];
mdata[1][45]=[0.96,-3.00,439.89];
mdata[1][46]=[1.30,-4.07,449.83];
mdata[1][47]=[1.70,-5.31,459.74];
mdata[1][48]=[2.15,-6.70,469.63];
mdata[1][49]=[2.64,-8.26,479.50];
mdata[1][50]=[3.19,-9.98,489.34];
mdata[1][51]=[3.80,-11.86,499.14];
mdata[1][52]=[4.45,-13.90,508.91];
mdata[1][53]=[5.15,-16.10,518.64];
mdata[1][54]=[5.91,-18.45,528.33];
mdata[1][55]=[6.71,-20.97,537.97];
mdata[1][56]=[7.57,-23.64,547.57];
mdata[1][57]=[8.47,-26.47,557.12];
mdata[1][58]=[9.43,-29.46,566.61];
mdata[1][59]=[10.43,-32.60,576.05];
mdata[1][60]=[11.49,-35.89,585.43];
mdata[1][61]=[12.59,-39.34,594.76];
mdata[1][62]=[13.75,-42.94,604.01];
mdata[1][63]=[14.95,-46.69,613.21];
mdata[1][64]=[16.19,-50.59,622.33];
mdata[1][65]=[17.49,-54.64,631.38];
mdata[1][66]=[18.83,-58.84,640.36];
mdata[1][67]=[20.22,-63.18,649.26];
mdata[1][68]=[21.66,-67.67,658.08];
mdata[1][69]=[23.14,-72.30,666.82];
mdata[1][70]=[24.67,-77.07,675.47];
mdata[1][71]=[26.24,-81.99,684.03];
mdata[1][72]=[27.86,-87.04,692.51];
mdata[1][73]=[29.52,-92.23,700.89];
mdata[1][74]=[31.23,-97.56,709.18];
mdata[1][75]=[32.09,-100.26,713.28];
mdata[1][76]=[32.97,-103.00,717.39];
mdata[1][77]=[34.72,-108.47,725.58];
mdata[1][78]=[36.47,-113.93,733.77];
mdata[1][79]=[38.22,-119.39,741.96];
mdata[1][80]=[39.97,-124.86,750.15];
mdata[1][81]=[41.72,-130.32,758.34];
mdata[1][82]=[43.46,-135.78,766.54];
mdata[1][83]=[45.21,-141.24,774.73];
mdata[1][84]=[46.96,-146.71,782.92];
mdata[1][85]=[48.71,-152.17,791.11];
mdata[1][86]=[50.46,-157.63,799.30];
mdata[1][87]=[52.21,-163.09,807.49];
mdata[1][88]=[53.96,-168.56,815.68];
mdata[1][89]=[55.70,-174.02,823.88];
mdata[1][90]=[57.45,-179.48,832.07];
mdata[1][91]=[59.20,-184.95,840.26];
mdata[1][92]=[60.95,-190.41,848.45];
mdata[1][93]=[62.70,-195.87,856.64];
mdata[1][94]=[64.45,-201.33,864.83];
mdata[1][95]=[66.20,-206.80,873.03];
mdata[1][96]=[67.94,-212.26,881.22];
mdata[1][97]=[69.69,-217.72,889.41];
mdata[1][98]=[71.44,-223.18,897.60];
mdata[1][99]=[72.20,-225.57,901.17];//}}}
//punkty rzutujące
mdata_pr[0]=[]; //{{{
mdata_pr[0][0]={'pidbox':0,'info':'(0.0, 0.0, 0.0)'};
mdata_pr[0][1]={'pidbox':96,'info':'(72.20, -225.57, 901.17)'};
mdata_pr[0][2]={'pidbox':15,'info':'(0.0, 0.0, 150.0)'};//}}}
mdata_pr[1]=[]; //{{{
mdata_pr[1][0]={'pidbox':0,'info':'(0.0, 0.0, 0.0)'};
mdata_pr[1][1]={'pidbox':99,'info':'(72.20, -225.57, 901.17)'};
mdata_pr[1][2]={'pidbox':38,'info':'(0.0, 0.0, 379.38)'};
mdata_pr[1][3]={'pidbox':75,'info':'(32.09, -100.26, 713.28)'};//}}}
//tytuł trajektorii
mdata_o[0]='Dwernik7A'; 
mdata_o[1]='Dwernik7K'; 

//ustawienie kolorów trajektorii
mdata_c[0]=0x6699ff;
mdata_c[1]=0xff0099;
*/
var najwiekszy_zakres;

var box_minmax = {
	'x': {'min': 0,'max':1000},
	'y': {'min': 0,'max':1000},
	'z': {'min': 0,'max':1000}
}

var box_zakres=1000;

var mdane_minmax=[];
var mdane=[]; 

var box_liczby_osie;

var wspolczynnik_transformacji;//przeliczanie współrzędnych 'dane' na 'box'
var dopasowany_zakres;

var mbox=[];

var mbox_rzut=[]; 

//przypisanie danych do tablicy 'dane', wyznaczenie minmax
function przypisz_dane_z_tablicy()//{{{
{
	//global: data, dane, dane_minmax

	var i;
	var j;

	//console.log('usunac przesuniecie data.js'); //{{{
	/*
	for(i=0;i<data.length;++i) 
	{ 
		//usunąć przesunięcie
		//dane[i]={'n':data[i][1]-1200,'e':data[i][2]-200,'t':data[i][3]+1400};//przesunięcie N o 5500
		dane[i]={'n':data[i][1],'e':data[i][0],'t':data[i][2]};//north,east,tvd

		if(i==0)
		{
			dane_minmax = {
				'n': {'min': dane[i].n,'max':dane[i].n},
				'e': {'min': dane[i].e,'max':dane[i].e},
				't': {'min': dane[i].t,'max':dane[i].t}
			}
		}

		if(dane[i].n<dane_minmax.n.min)
			dane_minmax.n.min=dane[i].n;
		if(dane[i].n>dane_minmax.n.max)
			dane_minmax.n.max=dane[i].n;

		if(dane[i].e<dane_minmax.e.min)
			dane_minmax.e.min=dane[i].e;
		if(dane[i].e>dane_minmax.e.max)
			dane_minmax.e.max=dane[i].e;

		if(dane[i].t<dane_minmax.t.min)
			dane_minmax.t.min=dane[i].t;
		if(dane[i].t>dane_minmax.t.max)
			dane_minmax.t.max=dane[i].t;
	}
	*/ //}}}

	for(j=0;j<imdata;j++)
	{
		mdane[j]=[];

		for(i=0;i<mdata[j].length;++i) 
		{
			mdane[j][i]=
			{
				'n':mdata[j][i][1],
				'e':mdata[j][i][0],
				't':mdata[j][i][2]
			};

			if(i==0)
			{
				mdane_minmax[j] = {
					'n': {'min': mdane[j][i].n,'max':mdane[j][i].n},
					'e': {'min': mdane[j][i].e,'max':mdane[j][i].e},
					't': {'min': mdane[j][i].t,'max':mdane[j][i].t}
				}
			}
			
			if(mdane[j][i].n<mdane_minmax[j].n.min)
				mdane_minmax[j].n.min=mdane[j][i].n;
			if(mdane[j][i].n>mdane_minmax[j].n.max)
				mdane_minmax[j].n.max=mdane[j][i].n;

			if(mdane[j][i].e<mdane_minmax[j].e.min)
				mdane_minmax[j].e.min=mdane[j][i].e;
			if(mdane[j][i].e>mdane_minmax[j].e.max)
				mdane_minmax[j].e.max=mdane[j][i].e;

			if(mdane[j][i].t<mdane_minmax[j].t.min)
				mdane_minmax[j].t.min=mdane[j][i].t;
			if(mdane[j][i].t>mdane_minmax[j].t.max)
				mdane_minmax[j].t.max=mdane[j][i].t;
		}
	}
}//}}}

przypisz_dane_z_tablicy();

delete data;
delete mdata;

function mznajdz_najwiekszy_zakres(mm)//{{{
{
	var najwiekszy_zakres={'d':0};
	var j;

	var tdminmax={};

	for(j=0;j<mm.length;j++)
	{
		if(j==0)
		{
			tdminmax.e={}; 
			tdminmax.n={}; 
			tdminmax.t={}; 

			tdminmax.e.min=mm[j].e.min;
			tdminmax.e.max=mm[j].e.max;

			tdminmax.n.min=mm[j].n.min;
			tdminmax.n.max=mm[j].n.max;

			tdminmax.t.min=mm[j].t.min;
			tdminmax.t.max=mm[j].t.max;
		}
		else
		{
			if(tdminmax.e.min>mm[j].e.min)
				tdminmax.e.min=mm[j].e.min; 
			if(tdminmax.e.max<mm[j].e.max)
				tdminmax.e.max=mm[j].e.max; 

			if(tdminmax.n.min>mm[j].n.min)
				tdminmax.n.min=mm[j].n.min; 
			if(tdminmax.n.max<mm[j].n.max)
				tdminmax.n.max=mm[j].n.max; 

			if(tdminmax.t.min>mm[j].t.min)
				tdminmax.t.min=mm[j].t.min; 
			if(tdminmax.t.max<mm[j].t.max)
				tdminmax.t.max=mm[j].t.max; 
		}
	}

	najwiekszy_zakres=znajdz_najwiekszy_zakres(tdminmax);
	
	return najwiekszy_zakres;
}//}}}
function znajdz_najwiekszy_zakres(mm)//{{{
{
	var najwiekszy_zakres={'d':0};
	
	if(najwiekszy_zakres.d<mm.n.max-mm.n.min)
	{
		najwiekszy_zakres.d=Math.abs(mm.n.max-mm.n.min);
		najwiekszy_zakres.min=mm.n.min;	
		najwiekszy_zakres.max=mm.n.max;	
	}
	
	if(najwiekszy_zakres.d<mm.e.max-mm.e.min)
	{
		najwiekszy_zakres.d=Math.abs(mm.e.max-mm.e.min);
		najwiekszy_zakres.min=mm.e.min;	
		najwiekszy_zakres.max=mm.e.max;	
	}

	if(najwiekszy_zakres.d<mm.t.max-mm.t.min)
	{
		najwiekszy_zakres.d=Math.abs(mm.t.max-mm.t.min);
		najwiekszy_zakres.min=mm.t.min;	
		najwiekszy_zakres.max=mm.t.max;	
	}
	
	return najwiekszy_zakres;
}//}}}
function znajdz_dopasowany_zakres(zakres)//{{{
{
	var i;
	var tab=[100,200,300,500,1000,2000,3000,4000,5000,6000,8000,10000,15000,20000,25000,30000,40000,50000,100000];
	
	var dopasowany_zakres;
	var d=zakres.d;

	for(i=0;i<tab.length;i++)
		if(d<=tab[i])
			break;

	if(zakres.min%d!=0)
		i++;
	
	dopasowany_zakres=tab[i];

	return dopasowany_zakres;
}//}}}
function wylicz_a(dopasowany_zakres,box_zakres)//{{{
{
	var a=box_zakres/dopasowany_zakres;
	return a;
}//}}}
function mwylicz_zakresy_dla_osi(d,zakres)//{{{
{
	var j; 
	j=0;

	var dd={
		'n': {'min': d[j].n.min,'max':d[j].n.min},
		'e': {'min': d[j].e.min,'max':d[j].e.min},
		't': {'min': d[j].t.min,'max':d[j].t.min}
	};

	for(j=0;j<d.length;j++)
	{
		if(dd.e.min>d[j].e.min)
			dd.e.min=d[j].e.min;

		if(dd.e.max<d[j].e.max)
			dd.e.max=d[j].e.max;

		if(dd.n.min>d[j].n.min)
			dd.n.min=d[j].n.min;

		if(dd.n.max<d[j].n.max)
			dd.n.max=d[j].n.max;

		if(dd.t.min>d[j].t.min)
			dd.t.min=d[j].t.min;

		if(dd.t.max<d[j].t.max)
			dd.t.max=d[j].t.max;
	}

	var osie={
		'n':{'start':null,'krok':null},
		'e':{'start':null,'krok':null},
		't':{'start':null,'krok':null}
	};

	var pocz;
	var reszta;
	var d_10;
	
	d_10=zakres/10;

	reszta=dd.n.min%d_10;
	pocz=dd.n.min-reszta;

	if(dd.n.min<0&&reszta!=0)
		pocz-=d_10;
	
	osie.n.start=pocz; 
	osie.n.krok=d_10;

	reszta=dd.e.min%d_10;
	pocz=dd.e.min-reszta;

	if(dd.e.min<0&&reszta!=0)
		pocz-=d_10;

	osie.e.start=pocz; 
	osie.e.krok=d_10;

	reszta=dd.t.min%d_10;
	pocz=dd.t.min-reszta;

	if(dd.t.min<0&&reszta!=0)
		pocz-=d_10;

	osie.t.start=pocz; 
	osie.t.krok=d_10;

	return osie;
}//}}}
function mprzetwarzanie_dane_na_box()//{{{
{
	//global: mdane, box, wspolczynnik_transformacji,box_liczby_osie
	var i=0; 
	var j;
	var P1;
	var P0=undefined;
	var P3; 
	var a=wspolczynnik_transformacji;

	for(j=0;j<mdane.length;j++)
	{
		mbox[j]=[];

		for(i=0;i<mdane[j].length;i++)
		{
			P1=mdane[j][i]; 

			P3={
				//'x': a*P1.n+trans_ab.xn.b, 
				'x': a*(P1.n-box_liczby_osie.n.start), 
				'z': a*(P1.e-box_liczby_osie.e.start), 
				//'y': a*(P1.t-box_liczby_osie.t.start), 
				// odwracanie osi y
				'y': -1*a*(P1.t-box_liczby_osie.t.start)+box_minmax.y.max 
			}

			if(P3!=P0)
				mbox[j].push(P3);

			P0=P3; 
		}

	}

}//}}}
function mpunkty_rzutowane()//{{{
{
	var i, j; 
	var P;

	for(j=0;j<imdata;j++)
	{
		mbox_rzut[j]=[];
		for(i=0;i<mdata_pr[j].length;i++)
		{
			P={}; 

			pidbox=mdata_pr[j][i].pidbox;

			P.x=mbox[j][pidbox].x;
			P.y=mbox[j][pidbox].y;
			P.z=mbox[j][pidbox].z;
			P.info=mdata_pr[j][i].info;
			P.pidbox=mdata_pr[j][i].pidbox;
			mbox_rzut[j].push(P);
		}
	}


}//}}}

mnajwiekszy_zakres=mznajdz_najwiekszy_zakres(mdane_minmax);//+
mdopasowany_zakres=znajdz_dopasowany_zakres(mnajwiekszy_zakres);//+
box_liczby_osie=mwylicz_zakresy_dla_osi(mdane_minmax,mdopasowany_zakres);//+
wspolczynnik_transformacji=wylicz_a(mdopasowany_zakres,box_zakres);//++
mprzetwarzanie_dane_na_box();
mpunkty_rzutowane();

function dodaj_selektor_trajektorii()//{{{
{
	//mdata_o
	var i; 
	for(i=0;i<imdata;i++)
		$('#wykres_trajektorie').append('<input class="trajektoria_item" value="'+i+'" type="checkbox" checked="checked">'+mdata_o[i]+'<br>'); 
}//}}}

dodaj_selektor_trajektorii();

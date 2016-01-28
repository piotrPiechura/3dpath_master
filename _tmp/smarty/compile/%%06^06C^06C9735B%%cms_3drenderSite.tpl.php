<?php /* Smarty version 2.6.20, created on 2015-12-13 22:26:48
         compiled from common/cms_3drenderSite.tpl */ ?>
 <?php if (! empty ( $this->_tpl_vars['trajectory'] )): ?>
       
    <div class="row"> 
        <div class="col-md-12 nocsroll">
           <div class="grid simple">
                <div class="grid-title">
                
                    <h4>Trajectory <span class="semi-bold"> 3D</span></h4>
                    <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                </div>
                <div class="grid-body">
                    <div>
                                 
                               <div id="wykres_conf">

				<div>
					<button id="menu_1" class="btn-on">PChILR</button>
					(punkty charakterystyczne i linie rzutujące)<br>
				</div>

				<div style="margin-top: 6px">
					<button id="menu_2" class="btn-on">KnŚ RnŚ</button>
					(krzyżyki na ścianach, rzuty na poszczególne ściany)<br>
					<input id="menu_2_1" type="checkbox" checked="checked">north
					<input id="menu_2_2" type="checkbox" checked="checked">east
					<input id="menu_2_3" type="checkbox" checked="checked">tvd
				</div>

				<div style="margin-top: 6px">
					<button id="menu_3" class="btn-on">Opisy 1</button>
					(wyświetlanie nazw otworów)<br>
				</div>

				<div style="margin-top: 6px">
					<button id="menu_4" class="btn-on">Opisy 2</button>
					(opisy punktów charakterystycznych)<br>
				</div>

				<div style="margin-top: 6px">

					<div>
						<span style="width: 30px; display: inline-block;">north</span>
						<input value="0" style="width: 200px" id="slideN" type="range" min="0" max="10" step="1">

					</div>

					<div>
						<span style="width: 30px; display: inline-block;">east</span>
						<input value="0" style="width: 200px" id="slideE" type="range" min="0" max="10" step="1">
					</div>

					<div>
						<span style="width: 30px; display: inline-block;">tvd</span>
						<input value="0" style="width: 200px" id="slideT" type="range" min="0" max="10" step="1">
					</div>

				</div>

				<div style="margin-top: 6px; width: 130px">
					<fieldset>
						<legend>Camera type</legend>
						<input type="radio" id="cameraP" name="camera" value="Perspective" checked="checked">Perspective<br>
						<input type="radio" id="cameraO" name="camera" value="Orthographic">Orthographic<br>

						<button id="ortho_east" class="btn-off">east</button>
						<button id="ortho_north" class="btn-off">north</button>
						<button id="ortho_tvd" class="btn-off">tvd</button>
					</fieldset>
				</div>

			</div>

			<div id="wykres_trajektorie" style="cloor: #aaa">

			<!--	
				<input id="wykres1" type="checkbox" checked="checked" disabled="disabled">wykres1<br>
				<input id="wykres2" type="checkbox" disabled="disabled">wykres2<br>
				<input id="wykres3" type="checkbox" disabled="disabled">wykres3<br>
				<input id="wykres4" type="checkbox" disabled="disabled">wykres4<br>
			-->

			<input class="trajektoria_item" value="0" type="checkbox" checked="checked">Dwernik7A<br><input class="trajektoria_item" value="1" type="checkbox" checked="checked">Dwernik7K<br></div>

                        <div id="wykres_3dpath" style="width: 1200px;"></div>
                        
                        
                            <script src="./3dpath v2_files/three.min.js"></script>

                            <script type="text/javascript" src="./3dpath v2_files/OrbitControls.js"></script>
                            <script type="text/javascript" src="./3dpath v2_files/Detector.js"></script>

                            <script src="./3dpath v2_files/gentilis_bold.typeface.js"></script>
                            <script src="./3dpath v2_files/gentilis_regular.typeface.js"></script>
                            <script src="./3dpath v2_files/optimer_bold.typeface.js"></script>
                            <script src="./3dpath v2_files/optimer_regular.typeface.js"></script>
                            <script src="./3dpath v2_files/helvetiker_bold.typeface.js"></script>
                            <script src="./3dpath v2_files/helvetiker_regular.typeface.js"></script>

                            <script type="text/javascript" src="./3dpath v2_files/menu.js"></script>

                            <!--make sure this is last-->

                            <script>
                                    //var data=[];

                                    var mdata=[]; 
                                    var mdata_wybrane=[];

                                    var mdata_pr=[];
                                    var mdata_o=[];
                                    var mdata_c=[];

                                    var imdata=1;//ilość trajektorii 
                                    
                                    mdata[0]=[]; 
                                    mdata[1]=[];
                                <?php $this->assign('licznik', 0); ?>
                                <?php $_from = $this->_tpl_vars['trajectoryRender']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['_trajectory']):
?> 
                                    mdata[<?php echo $this->_tpl_vars['licznik']; ?>
]=[]; 
                                    

                                    mdata_wybrane[<?php echo $this->_tpl_vars['licznik']; ?>
]=1;

                                    mdata[<?php echo $this->_tpl_vars['licznik']; ?>
]=[];
                                    mdata[<?php echo $this->_tpl_vars['licznik']; ?>
][0]=[0.00,0.00,0.00];
                                    <?php $this->assign('points', $this->_tpl_vars['_trajectory']); ?> 
                                    <?php $_from = $this->_tpl_vars['points']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['GeometryPoints'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['GeometryPoints']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_key'] => $this->_tpl_vars['_point']):
        $this->_foreach['GeometryPoints']['iteration']++;
?>
                                                                            mdata[<?php echo $this->_tpl_vars['licznik']; ?>
][<?php echo $this->_tpl_vars['_key']; ?>
]=[<?php echo $this->_tpl_vars['_point']['x']; ?>
,<?php echo $this->_tpl_vars['_point']['y']; ?>
, <?php echo $this->_tpl_vars['_point']['z']; ?>
];
                                    <?php endforeach; endif; unset($_from); ?>  

                                    mdata_o[<?php echo $this->_tpl_vars['licznik']; ?>
]='well <?php echo $this->_tpl_vars['licznik']; ?>
';

                                    mdata_pr[<?php echo $this->_tpl_vars['licznik']; ?>
]=[];
                                        

                                    mdata_c[<?php echo $this->_tpl_vars['licznik']; ?>
]=0x6699ff;
                              <?php $this->assign('licznik', $this->_tpl_vars['licznik']+1); ?>
                              <?php endforeach; endif; unset($_from); ?>
                                  var imdata=<?php echo $this->_tpl_vars['licznik']; ?>
;//ilość trajektorii 
                            </script>
                            <script src="./3dpath v2_files/data.js"></script> 
                            <script src="./3dpath v2_files/scene.js"></script> 
                        
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    <?php endif; ?>            
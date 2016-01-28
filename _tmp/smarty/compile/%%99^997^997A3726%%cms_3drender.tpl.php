<?php /* Smarty version 2.6.20, created on 2015-12-11 15:54:49
         compiled from common/cms_3drender.tpl */ ?>
 <?php if (! empty ( $this->_tpl_vars['trajectory3DRender']['1'] )): ?>
     
        
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

			<input class="trajektoria_item" value="0" type="checkbox" checked="checked"><?php echo $this->_tpl_vars['recordOldValues']['wellName']; ?>
<br>

      <div id="wykres_3dpath" style="width: 1200px;"><canvas width="1200" height="800" ></canvas></div>

      <!-- make sure this is just below the closing </body> tag -->
      <!-- for some reason the latest three.js doesn't work, so we use the one from the example file-->
   

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

                        mdata_wybrane[0]=1;
                        
                        mdata[0]=[];
                        mdata[0][0]=[0.00,0.00,0.00];
                    <?php $_from = $this->_tpl_vars['trajectory3DRender']['1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['Points'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['Points']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_key'] => $this->_tpl_vars['_point']):
        $this->_foreach['Points']['iteration']++;
?>
                        mdata[0][<?php echo $this->_tpl_vars['_key']; ?>
]=[<?php echo $this->_tpl_vars['_point']['x']; ?>
,<?php echo $this->_tpl_vars['_point']['y']; ?>
, <?php echo $this->_tpl_vars['_point']['z']; ?>
];
                    <?php endforeach; endif; unset($_from); ?>  
                     
                    mdata_o[0]='<?php echo $this->_tpl_vars['recordOldValues']['wellName']; ?>
';
                    
                    mdata_pr[0]=[];
                    <?php echo 'mdata_pr[0][0]={\'pidbox\':0,\'info\':\'(0.0, 0.0, 0.0)\'};'; ?>

                    <?php $_from = $this->_tpl_vars['trajectoryHaracteristicPoints']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['HPoints'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['HPoints']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_hkey'] => $this->_tpl_vars['_point']):
        $this->_foreach['HPoints']['iteration']++;
?>
                        mdata_pr[0][<?php echo $this->_foreach['HPoints']['iteration']; ?>
]=<?php echo '{'; ?>
'pidbox':<?php echo $this->_tpl_vars['_hkey']; ?>
,'info':'(<?php echo $this->_tpl_vars['_point']['x']; ?>
,<?php echo $this->_tpl_vars['_point']['y']; ?>
, <?php echo $this->_tpl_vars['_point']['z']; ?>
)'<?php echo '}'; ?>
;
                    <?php endforeach; endif; unset($_from); ?>    
                    
                  mdata_c[0]=0x6699ff;
                    
                </script>
                <script src="./3dpath v2_files/data.js"></script> 
		<script src="./3dpath v2_files/scene.js"></script> 


                            
                    </div>
                </div>
             </div>
        </div>
    </div>
    <?php endif; ?>
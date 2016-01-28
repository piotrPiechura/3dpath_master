{if !empty($trajectory)}
{* <script src="assets/js/3dTree/Three.js"></script>
 <script src="assets/js/3dTree/Detector.js"></script>
 <script src="assets/js/3dTree/Stats.js"></script>
 <script src="assets/js/3dTree/OrbitControls.js"></script>

 <script src="assets/js/3dTree/js/cameras/CombinedCamera.js"></script>
 <script src="assets/js/3dTree/js/renderers/Projector.js"></script>
 <script src="assets/js/3dTree/js/renderers/CanvasRenderer.js"></script>

 <script src="assets/js/3dTree/THREEx.KeyboardState.js"></script>
 <script src="assets/js/3dTree/THREEx.FullScreen.js"></script>
 <script src="assets/js/3dTree/THREEx.WindowResize.js"></script>

     <!-- load fonts -->
<script src="fonts/gentilis_bold.typeface.js"></script>
<script src="fonts/gentilis_regular.typeface.js"></script>
<script src="fonts/optimer_bold.typeface.js"></script>
<script src="fonts/optimer_regular.typeface.js"></script>
<script src="fonts/helvetiker_bold.typeface.js"></script>
<script src="fonts/helvetiker_regular.typeface.js"></script>
<script src="fonts/droid_sans_regular.typeface.js"></script>
<script src="fonts/droid_sans_bold.typeface.js"></script>
<script src="fonts/droid_serif_regular.typeface.js"></script>
<script src="fonts/droid_serif_bold.typeface.js"></script>*}
<script type="text/javascript" src="assets/js/jquery.ezmark.js" ></script>

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
                        <div class="fieldset" style="width: auto; background: #FCFCFC">

                            <!-- GRID START -->
                            <div class="fieldset fieldset-grid">
                                <span class="legend">Grid</span>
                                <div class="clear"></div>
                                <label>Points & lines</label>
                                <div  class="buttons radios" >
                                    <label for="PChILR_1">On</label>
                                    <input id="PChILR_1" name="PChILR" type="radio"  />
                                    <label for="PChILR_2">Off</label>
                                    <input id="PChILR_2" name="PChILR" type="radio" />
                                </div>
                                <div class="clear"></div>

                                <label>Crosses, projections</label>
                                <div  class="buttons radios" >
                                    <label for="KnS_1">On</label>
                                    <input id="KnS_1" name="KnS" type="radio"  />
                                    <label for="KnS_2">Off</label>
                                    <input id="KnS_2" name="KnS" type="radio" />
                                </div>
                                <div class="clear"></div>
                                <div class="KnS_directors" style="display: none; text-align: center;">
                                    <div class="chbList">
                                        <div class="ez-checkbox">
                                            <input id="KnS_north"   name="KnS_north" class="checkboxIt ez-hide" type="checkbox">
                                        </div>
                                        <label class="checkbox-ez-custom" >North</label>
                                    </div>

                                    <div class="chbList">
                                        <div class="ez-checkbox">
                                            <input id="KnS_east"  name="KnS_east" class="checkboxIt ez-hide" type="checkbox">
                                        </div>
                                        <label class="checkbox-ez-custom" >East</label>
                                    </div>

                                    <div class="chbList">
                                        <div class="ez-checkbox">
                                            <input id="KnS_tvd"   name="KnS_tvd" class="checkboxIt ez-hide" type="checkbox">
                                        </div>
                                        <label class="checkbox-ez-custom" >TVD</label>
                                    </div>
                                </div>
                            </div>
                            <!-- GRID END -->
                            <!-- DESCRIPTIONS START -->
                            <div class="fieldset fieldset-grid small-set">
                                <span class="legend">Descriptions</span>
                                <div class="clear"></div>
                                <label class="small-set">Names of wells</label>
                                <div  class="buttons radios" >
                                    <label for="NoW_1">On</label>
                                    <input id="NoW_1" name="wellname" type="radio" value="1"/>
                                    <label for="NoW_2">Off</label>
                                    <input id="NoW_2" name="wellname" type="radio" value="0"/>
                                </div>
                                <div class="clear"></div>

                                <label class="small-set">Landmarks</label>
                                <div  class="buttons radios" >
                                    <label for="pktchar_1">On</label>
                                    <input id="pktchar_1" name="pktchar" type="radio" value="1"/>
                                    <label for="pktchar_2">Off</label>
                                    <input id="pktchar_2" name="pktchar" type="radio" value="0"/>
                                </div>
                            </div>
                            <!-- DESCRIPTIONS END -->
                            <!-- TRANSLATION START -->
                            <div class="fieldset fieldset-grid medium-set">
                                <span class="legend">Axis transform</span>
                                <div class="clear"></div>
                                <span class="translation">North</span>
                                <input  id="slideN" type="range" min="0" max="5000" step="500" value="0"/>
                                <div class="clear"></div>
                                <span class="translation">East</span>
                                <input id="slideE" type="range" min="0" max="5000" step="500" value="0"/>
                                <div class="clear"></div>
                                <span class="translation">TVD</span>
                                <input id="slideT" type="range" min="0" max="5000" step="500" value="0"/>
                            </div>
                            <!-- TRANSLATION END -->
                            <!-- CAMERA START -->
                            <div class="fieldset fieldset-grid ">
                                <span class="legend" >Camera</span>
                                <div class="clear"></div>
                                <span class="translation">Mode</span>
                                <div  class="buttons radios" >
                                    <label for="camera_1">Perspective</label>
                                    <input id="camera_1" name="type_camera" type="radio" value="1"/>
                                    <label for="camera_2">Orthographic</label>
                                    <input id="camera_2" name="type_camera" type="radio" value="0"/>
                                </div>
                                <div class="clear"></div>
                                <div class="Camera_directors" style="display: none; text-align: center;">

                                    <div class="chbList">
                                        <div class="ez-checkbox">
                                            <input id="camera_north"  name="radio_1112" class="checkboxIt ez-hide" type="radio">
                                        </div>
                                        <label class="checkbox-ez-custom" >North</label>
                                    </div>

                                    <div class="chbList">
                                        <div class="ez-checkbox">
                                            <input id="camera_east"  name="radio_1112" class="checkboxIt ez-hide" type="radio">
                                        </div>
                                        <label class="checkbox-ez-custom" >East</label>
                                    </div>

                                    <div class="chbList">
                                        <div class="ez-checkbox">
                                            <input id="camera_tvd"   name="radio_1112" class="checkboxIt ez-hide" type="radio">
                                        </div>
                                        <label class="checkbox-ez-custom" >TVD</label>
                                    </div>
                                </div>
                            </div>
                            <!-- CAMERA END -->

                            <!-- WELLS START -->
                            <div class="fieldset fieldset-grid">
                                <span class="legend">Wells</span>
                                <div class="clear"></div>
                                <div class="menu_wells" >
                                    <!-- INPUTS WITH WELLS  FROM data.js -->
                                </div>
                            </div>
                            <!-- WELLS END -->
                        </div>


                    </div>
                    <!-- prety_menu -->

                    <script type="text/javascript" src="./3dpath v2_files/prety_menu.js"></script>
                    <!-- prety_menu -->

                    <div id="wykres_trajektorie" style="cloor: #aaa">

                        <!--
                            <input id="wykres1" type="checkbox" checked="checked" disabled="disabled">wykres1<br>
                            <input id="wykres2" type="checkbox" disabled="disabled">wykres2<br>
                            <input id="wykres3" type="checkbox" disabled="disabled">wykres3<br>
                            <input id="wykres4" type="checkbox" disabled="disabled">wykres4<br>
                        -->


                        <div id="wykres_3dpath" style="width: 1200px;"></div>
                        <script type="text/javascript" src="assets/js/tooltip-range.js" ></script>
                        <script src='assets/js/styl.min.js'></script>


                        <script src="./3dpath v2_files/three.min.js"></script>

                        <script type="text/javascript" src="./3dpath v2_files/OrbitControls.js"></script>
                        <script type="text/javascript" src="./3dpath v2_files/Detector.js"></script>

                        <script src="./3dpath v2_files/gentilis_bold.typeface.js"></script>
                        <script src="./3dpath v2_files/gentilis_regular.typeface.js"></script>
                        <script src="./3dpath v2_files/optimer_bold.typeface.js"></script>
                        <script src="./3dpath v2_files/optimer_regular.typeface.js"></script>
                        <script src="./3dpath v2_files/helvetiker_bold.typeface.js"></script>
                        <script src="./3dpath v2_files/helvetiker_regular.typeface.js"></script>

                        <!-- <script type="text/javascript" src="./3dpath v2_files/menu.js"></script>t-->

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
                            {assign var="licznik" value=0}
                            {foreach item="_trajectory" from=$trajectoryRender key="key"}
                            mdata[{$licznik}]=[];


                            mdata_wybrane[{$licznik}]=1;

                            mdata[{$licznik}]=[];
                            mdata[{$licznik}][0]=[0.00,0.00,0.00];
                            {assign var="points" value=$_trajectory}
                            {foreach item="_point" from=$points key="_key" name="GeometryPoints"}
                            {*foreach item="_point" from=$trajectory3DRender.1 key="_key" name="Points"*}
                            mdata[{$licznik}][{$_key}]=[{$_point.x},{$_point.y}, {$_point.z}];
                            {/foreach}

                            mdata_o[{$licznik}]='well {$licznik}';

                            mdata_pr[{$licznik}]=[];
                            {*literal}mdata_pr[{$licznik}][0]={'pidbox':0,'info':'(0.0, 0.0, 0.0)'};{/literal}
                            {foreach item="_point" from=$trajectoryHaracteristicPoints key="_hkey" name="HPoints"}
                                mdata_pr[{$licznik}][{$smarty.foreach.HPoints.iteration}]={literal}{{/literal}'pidbox':{$_hkey},'info':'({$_point.x},{$_point.y}, {$_point.z})'{literal}}{/literal};
                            {/foreach*}

                            mdata_c[{$licznik}]=0x6699ff;
                            {assign var="licznik" value=$licznik+1}
                            {/foreach}
                            var imdata={$licznik};//ilość trajektorii
                        </script>
                        <script src="./3dpath v2_files/data.js"></script>
                        <script src="./3dpath v2_files/scene.js"></script>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {/if}            
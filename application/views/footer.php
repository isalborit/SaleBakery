	</div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalSettings">
        <div class="modal-dialog" style="width:380px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Configuraciones</h4>
                </div>
                <div class="modal-body">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-primary">
                            <div class="panel-heading hoverPanel" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <h4 class="panel-title">
                                    <a href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-lock"></span> Cambio de contraseña
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form id="formSettingsPassword">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon" style="width:160px;"><strong>Actual contraseña</strong></span>
                                            <input type="password" name="pass-1" class="form-control" aria-describedby="basic-addon" placeholder="********" style="width:160px;" required>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon" style="width:160px;"><strong>Nueva contraseña</strong></span>
                                            <input type="password" name="pass-2" class="form-control" aria-describedby="basic-addon" placeholder="********" style="width:160px;" required>
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon" style="width:160px;"><strong>Confirmar contraseña</strong></span>
                                            <input type="password" name="pass-3" class="form-control" aria-describedby="basic-addon" placeholder="********" style="width:160px;" required>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-success btn-block">
                                            <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($_SESSION['adm_sv'])){ ?>
                            <div class="panel panel-primary">
                                <div class="panel-heading hoverPanel" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    <h4 class="panel-title">
                                        <a href="javascript:void(0);">
                                            <span class="glyphicon glyphicon-list-alt"></span> Cambio de series
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <form id="formSettingsSeries">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Factura electrónica</strong></span>
                                                <input type="text" name="serie-1" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$serie1;?>" maxlength="4">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Boleta electrónica</strong></span>
                                                <input type="text" name="serie-2" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$serie2;?>" maxlength="4">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Venta alternativa</strong></span>
                                                <input type="text" name="serie-3" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$serie3;?>" maxlength="4">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Nota Crédito Factura</strong></span>
                                                <input type="text" name="serie-4" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$serie4;?>" maxlength="4">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Nota Crédito Boleta</strong></span>
                                                <input type="text" name="serie-5" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$serie5;?>" maxlength="4">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Nota de Pedido</strong></span>
                                                <input type="text" name="serie-6" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$serie6;?>" maxlength="4">
                                            </div>
                                            <br>
                                            <button type="submit" class="btn btn-success btn-block">
                                                <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading hoverPanel" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                    <h4 class="panel-title">
                                        <a href="javascript:void(0);">
                                            <span class="glyphicon glyphicon-credit-card"></span> Calculos Economicos
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <form id="formSettingsMoney">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Gastos Mensuales</strong></span>
                                                <input type="text" name="datos-1" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$datos1;?>">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Impuesto a la Renta</strong></span>
                                                <input type="text" name="datos-2" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$datos2;?>" maxlength="4">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Porcentaje/Gastos</strong></span>
                                                <input type="text" name="datos-3" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$datos3;?>">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>IGV</strong></span>
                                                <input type="text" name="datos-4" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$datos4;?>">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon" style="width:180px;"><strong>Tipo de Cambio</strong></span>
                                                <input type="text" name="datos-5" class="form-control" aria-describedby="basic-addon" style="width:140px;" required value="<?=$datos5;?>">
                                            </div>
                                            <br>
                                            <button type="submit" class="btn btn-success btn-block">
                                                <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
	<footer>
		<p>Copyright © -  CORP. MULTISERVICE MISTER PAN 2018</p>
	</footer>
	<script src="<?=base_url('public')?>/js/jquery.js"></script>
    <script src="<?=base_url('public')?>/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=base_url('public')?>/datatables/jquery.dataTables.min.js"></script>
    <script src="<?=base_url('public')?>/datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?=base_url('public')?>/js/jquery-ui.js"></script>
    <script src="<?=base_url('public')?>/js/sorttable.js"></script>
    <script src="<?=base_url('public')?>/js/myjava.js"></script>
    <script>var baseUrl = '<?php echo base_url();?>';</script>
</body>
</html>
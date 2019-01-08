<?php if(!class_exists('Rain\Tpl')){exit;}?><!--content wrapper. contais page content-->
<div class="content-wrapper">
        <!--Content header (page header)-->
        <section class="content-header">
            <h1>Lista de Usuarios</h1>
            <ol class="breadcrumb">
                <li><a href="/admin"><i class="fa fa-dashboard"></i>Home</a></li>
                <li><a href="/admin"><i class="fa fa-dashboard"></i>Usuarios</a></li>
            </ol>
        </section>
    
        <!--Main Content-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Editar Usuarios</h3>
                        </div>
                        <!--/.box-header-->
                        <!--form start-->
                        <form role="form" action="/admin/users/<?php echo htmlspecialchars( $user["iduser"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" method="post">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="desperson">Nome</label>
                                    <input type="text" id="desperson" class="form-control" name="desperson" placeholder="Digite o Nome" value="<?php echo htmlspecialchars( $user["desperson"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="deslogin">Login</label>
                                    <input type="text" id="deslogin" class="form-control" name="deslogin" placeholder="Digite o Login" value="<?php echo htmlspecialchars( $user["deslogin"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="nrphone">Telefone</label>
                                    <input type="tel" id="nrphone" class="form-control" name="nrphone" placeholder="Digite o Telefone" value="<?php echo htmlspecialchars( $user["nrphone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="desemail">Email</label>
                                    <input type="email" id="desemail" class="form-control" name="desemail" placeholder="Digite o Email" value="<?php echo htmlspecialchars( $user["desemail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                                </div>
                                <div class="checkbox"><label for=""><input type="checkbox" id="inadmin" value="1"  <?php if( $user["inadmin"] ==1 ){ ?>checked<?php } ?>> Acesso de Administrador</label></div>
                            </div>
                            <!--.box-body-->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Editar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
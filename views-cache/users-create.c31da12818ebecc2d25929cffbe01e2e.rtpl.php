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
                            <h3 class="box-title">Novo Usuario</h3>
                        </div>
                        <!--/.box-header-->
                        <!--form start-->
                        <form role="form" action="/admin/users/create" method="post">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="desperson">Nome</label>
                                    <input type="text" id="desperson" class="form-control" name="desperson" placeholder="Digite o Nome">
                                </div>
                                <div class="form-group">
                                    <label for="deslogin">Login</label>
                                    <input type="text" id="deslogin" class="form-control" name="deslogin" placeholder="Digite o Login">
                                </div>
                                <div class="form-group">
                                    <label for="nrphone">Telefone</label>
                                    <input type="tel" id="nrphone" class="form-control" name="nrphone" placeholder="Digite o Telefone">
                                </div>
                                <div class="form-group">
                                    <label for="desemail">Email</label>
                                    <input type="email" id="desemail" class="form-control" name="desemail" placeholder="Digite o Email">
                                </div>
                                <div class="form-group">
                                    <label for="despassword">Senha</label>
                                    <input type="password" id="despassword" class="form-control" name="despassword" placeholder="Digite a Senha">
                                </div>
                                <div class="checkbox"><label for=""><input type="checkbox" id="inadmin" name="inadmin"  values="1"> Acesso de Administrador</label></div>
                            </div>
                            <!--.box-body-->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php

namespace Views\Layout;


use App\Traits\App;
use Exception;

class AsideBar
{
    use App;

    static function asideBar()
    {
      $path = self::PATH;
      $path_dist = self::PATH_DIST;
      $path_storage=self::PATH_STORAGE;
        try {
            

            $html = <<<HTML
             <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.html" class="brand-link">
      <img src="{$path_storage}images/logo-adventa.jpeg" alt="adventa-logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Adventa</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{$path_dist}img/avatar.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alfredo Segura</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Buscar,articulos,modelo..." aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        
          <li class="nav-item">
                <a href="../HOME/" class="nav-link">
                <i class="fa-solid fa-house-user"></i>
                  <p>Inicio</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../MnUsuario/" class="nav-link ">
                <i class="fa-solid fa-table-list"></i>
                  <p>Lista de productos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../ConsultarTicket/" class="nav-link ">
                <i class="fa-solid fa-bag-shopping"></i>
                  <p>Mis de pedidos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{$path}views/auth/logout/" class="nav-link " id="logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                  <p>Cerrar Sesión</p>
                </a>
              </li>
        
         
     
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
HTML;
            echo $html;
        } catch (Exception $e) {
        }
    }
}

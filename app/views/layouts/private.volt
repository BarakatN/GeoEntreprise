<div class="navbar navbar-inverse">
  <div class="navbar-inner">
    <div class="container" style="width: auto;">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>

      </a>
      {{ link_to(null, 'class': 'brand', 'GeoEntreprise')}}
        <div class="nav-collapse">

          <ul class="nav">

            {%- set menus = [
              'Home': null,
              'Users': 'users',
              'Profiles': 'profiles',
              'Permissions': 'permissions',
<<<<<<< HEAD
              'Entreprise':'entreprise',
=======
              'Entreprises': 'entreprise'
              'Employés': 'categorieemploye',
>>>>>>> 585bdd5219e4dbefe487fbaaccec5160a2011ea1
              'Contact': 'contact',
              'Transport':'transport',
              'Departement':'departement',
              'Etablissement':'etablissement',
              'Domaine':'domaine'
<<<<<<< HEAD

=======
              
>>>>>>> 585bdd5219e4dbefe487fbaaccec5160a2011ea1




            ] -%}

            {%- for key, value in menus %}
              {% if value == dispatcher.getControllerName() %}
              <li class="active">{{ link_to(value, key) }}</li>
              {% else %}
              <li>{{ link_to(value, key) }}</li>
              {% endif %}
            {%- endfor -%}

          </ul>

        <ul class="nav pull-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ auth.getName() }} <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li>{{ link_to('users/changePassword', 'Change Password') }}</li>
            </ul>
          </li>
          <li>{{ link_to('session/logout', 'Logout') }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container">
  {{ content() }}
</div>

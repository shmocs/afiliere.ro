<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/avatar.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Admin</p>

                <a href="#"><i class="fa fa-circle text-success"></i>Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
	    
	    <?php
	    //\yii\helpers\VarDumper::dump(Yii::$app->user);
	    \yii\helpers\VarDumper::dump(Yii::$app->controller->id);

	    $menu = [
		    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
		    'items' => [
			    ['label' => 'Menu', 'options' => ['class' => 'header']],
			    ['label' => 'Dashboard', 'icon' => 'area-chart', 'url' => ['/']],
			    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
			    //['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
			    [
				    'label' => 'Some tools',
				    'icon' => 'gears',
				    'url' => '#', 'visible' => false,
				    'items' => [
					    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
					    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
					    [
						    'label' => 'Level One',
						    'icon' => 'circle-o',
						    'url' => '#',
						    'items' => [
							    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
							    [
								    'label' => 'Level Two',
								    'icon' => 'circle-o',
								    'url' => '#',
								    'items' => [
									    ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
									    ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
								    ],
							    ],
						    ],
					    ],
				    ],
			    ],
			    [
				    'label' => 'Sales',
				    'icon' => 'dollar',
				    'url' => '/sales/index',
				    'options' => [
				        'class' => (Yii::$app->controller->id == 'sales') ? 'menu-open active' : '',
					],
				    'items' => [
					    ['label' => '2Performant', 'icon' => 'share', 'url' => ['/sales/index?platform=2performant'], 'options' => ['class' => ( isset($_GET['platform']) && $_GET['platform'] == '2performant') ? 'active' : ''],],
					    ['label' => 'ProfitShare', 'icon' => 'share', 'url' => ['/sales/index?platform=profitshare'], 'options' => ['class' => ( isset($_GET['platform']) && $_GET['platform'] == 'profitshare') ? 'active' : ''],],
				    ],
			    ],
			    [
				    'label' => 'Export',
				    'icon' => 'download',
				    'url' => '/payments/index',
				    'options' => [
					    'class' => (Yii::$app->controller->id == 'payments') ? 'menu-open active' : '',
				    ],
				    'items' => [
					    ['label' => 'AdWords', 'icon' => 'share', 'url' => ['/payments/index?platform=adwords'], 'options' => ['class' => ( isset($_GET['platform']) && $_GET['platform'] == 'adwords') ? 'active' : ''],],
				    ],
			    ],
			    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
			    ['label' => 'Logout' . ' ('.Yii::$app->user->identity->username.')', 'icon' => 'power-off', 'url' => ['site/logout'], 'visible' => !Yii::$app->user->isGuest],
		    ],
	    ];
	    echo dmstr\widgets\Menu::widget($menu)
	    
	    ?>

    </section>

</aside>

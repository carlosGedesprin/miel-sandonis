<?php


$r->addRoute(['GET', 'POST'], '/dashboard', 'views/control_panel/controlpanelViewController:dashboardAction');
$r->addRoute(['GET', 'POST'], '/panel_de_control', 'views/control_panel/controlpanelViewController:dashboardAction');

$r->addRoute(['GET', 'POST'], '/customers', 'views/control_panel/customerListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/customer/edit/{account_key}', 'views/control_panel/customerEditViewController:edititemAction');
//$r->addRoute(['GET', 'POST'], '/customer/delete/{account_key}', 'views/control_panel/customerDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/clientes', 'views/control_panel/customerListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/cliente/edit/{account_key}', 'views/control_panel/customerEditViewController:edititemAction');
//$r->addRoute(['GET', 'POST'], '/customer/delete/{account_key}', 'views/control_panel/customerDeleteViewController:deleteitemAction');

// Not used
$r->addRoute(['GET', 'POST'], '/certifications', 'views/control_panel/certificationListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/certification/edit/{id:\d+}', 'views/control_panel/certificationEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/certification/delete/{id:\d+}', 'views/control_panel/certificationDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/certificaciones', 'views/control_panel/certificationListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/certificacion/edit/{id:\d+}', 'views/control_panel/certificationEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/certificacion/delete/{id:\d+}', 'views/control_panel/certificationDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/my_account', 'views/control_panel/accountEditViewController:edititemAction'); //Pending
$r->addRoute(['GET', 'POST'], '/mi_cuenta', 'views/control_panel/accountEditViewController:edititemAction'); //Pending

$r->addRoute(['GET', 'POST'], '/my_users', 'views/control_panel/userEditViewController:itemslistAction'); //Pending
$r->addRoute(['GET', 'POST'], '/my_user/edit/{id:\d+}', 'views/control_panel/userEditViewController:edititemAction'); //Pending
$r->addRoute(['GET', 'POST'], '/my_user/delete/{id:\d+}', 'views/control_panel/userEditViewController:deleteitemAction'); //Pending
$r->addRoute(['GET', 'POST'], '/mis_usuarios', 'views/control_panel/userViewController:itemslistAction'); //Pending
$r->addRoute(['GET', 'POST'], '/mi_usuario/edit/{id:\d+}', 'views/control_panel/userEditViewController:edititemAction'); //Pending
$r->addRoute(['GET', 'POST'], '/mi_usuario/delete/{id:\d+}', 'views/control_panel/userDeleteViewController:deleteitemAction'); //Pending

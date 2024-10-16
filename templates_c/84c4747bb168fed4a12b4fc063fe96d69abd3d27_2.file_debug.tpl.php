<?php
/* Smarty version 5.4.1, created on 2024-10-09 11:46:10
  from 'file:E:\xampp\htdocs\libs\smarty\vendor\smarty\smarty\src/debug.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.1',
  'unifunc' => 'content_670650e2aea4f9_51534692',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '84c4747bb168fed4a12b4fc063fe96d69abd3d27' => 
    array (
      0 => 'E:\\xampp\\htdocs\\libs\\smarty\\vendor\\smarty\\smarty\\src/debug.tpl',
      1 => 1724973045,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_670650e2aea4f9_51534692 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'E:\\xampp\\htdocs\\libs\\smarty\\vendor\\smarty\\smarty\\src';
$_smarty_tpl->getSmarty()->getRuntime('Capture')->open($_smarty_tpl, '_smarty_debug', 'debug_output', null);?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Smarty Debug Console</title>
        <style>
            
            body, h1, h2, h3, td, th, p {
                font-family: sans-serif;
                font-weight: normal;
                font-size: 0.9em;
                margin: 1px;
                padding: 0;
            }

            h1 {
                margin: 0;
                text-align: left;
                padding: 2px;
                background-color: #f0c040;
                color: black;
                font-weight: bold;
                font-size: 1.2em;
            }

            h2 {
                background-color: #9B410E;
                color: white;
                text-align: left;
                font-weight: bold;
                padding: 2px;
                border-top: 1px solid black;
            }

            h3 {
                text-align: left;
                font-weight: bold;
                color: black;
                font-size: 0.7em;
                padding: 2px;
            }

            body {
                background: black;
            }

            p, table, div {
                background: #f0ead8;
            }

            p {
                margin: 0;
                font-style: italic;
                text-align: center;
            }

            table {
                width: 100%;
            }

            th, td {
                font-family: monospace;
                vertical-align: top;
                text-align: left;
            }

            td {
                color: green;
            }

            tr:nth-child(odd) {
                background-color: #eeeeee;
            }

            tr:nth-child(even) {
                background-color: #fafafa;
            }

            .exectime {
                font-size: 0.8em;
                font-style: italic;
            }

            #bold div {
                color: black;
                font-weight: bold;
            }

            #blue h3 {
                color: blue;
            }

            #normal div {
                color: black;
                font-weight: normal;
            }

            #table_assigned_vars th {
                color: blue;
                font-weight: bold;
            }

            #table_config_vars th {
                color: maroon;
            }
            
        </style>
    </head>
    <body>

    <h1>Smarty <?php echo htmlspecialchars((string) (\Smarty\Smarty::SMARTY_VERSION), ENT_QUOTES, 'UTF-8');?>
 Debug Console
        -  <?php if ((null !== ($_smarty_tpl->getValue('template_name') ?? null))) {
echo $_smarty_tpl->getSmarty()->getModifierCallback('debug_print_var')($_smarty_tpl->getValue('template_name'));?>
 <?php }
if (!empty($_smarty_tpl->getValue('template_data'))) {?>Total Time <?php echo htmlspecialchars((string) (sprintf("%.5f",$_smarty_tpl->getValue('execution_time'))), ENT_QUOTES, 'UTF-8');
}?></h1>

    <?php if (!empty($_smarty_tpl->getValue('template_data'))) {?>
        <h2>included templates &amp; config files (load time in seconds)</h2>
        <div>
            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('template_data'), 'template');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('template')->value) {
$foreach0DoElse = false;
?>
                <span style="color: brown;"><?php echo htmlspecialchars((string) ($_smarty_tpl->getValue('template')['name']), ENT_QUOTES, 'UTF-8');?>
</span>
                <br>&nbsp;&nbsp;<span class="exectime">
                (compile <?php echo htmlspecialchars((string) (sprintf("%.5f",$_smarty_tpl->getValue('template')['compile_time'])), ENT_QUOTES, 'UTF-8');?>
) (render <?php echo htmlspecialchars((string) (sprintf("%.5f",$_smarty_tpl->getValue('template')['render_time'])), ENT_QUOTES, 'UTF-8');?>
) (cache <?php echo htmlspecialchars((string) (sprintf("%.5f",$_smarty_tpl->getValue('template')['cache_time'])), ENT_QUOTES, 'UTF-8');?>
)
                 </span>
                <br>
            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </div>
    <?php }?>

    <h2>assigned template variables</h2>

    <table id="table_assigned_vars">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('assigned_vars'), 'vars');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('vars')->key => $_smarty_tpl->getVariable('vars')->value) {
$foreach1DoElse = false;
$foreach1Backup = clone $_smarty_tpl->getVariable('vars');
?>
            <tr>
                <td>
                    <h3 style="color: blue;">$<?php echo htmlspecialchars((string) ($_smarty_tpl->getVariable('vars')->key), ENT_QUOTES, 'UTF-8');?>
</h3>
                    <?php if ((null !== ($_smarty_tpl->getValue('vars')['nocache'] ?? null))) {?><strong>Nocache</strong><br><?php }?>
                    <?php if ((null !== ($_smarty_tpl->getValue('vars')['scope'] ?? null))) {?><strong>Origin:</strong> <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('debug_print_var')($_smarty_tpl->getValue('vars')['scope']);
}?>
                </td>
                <td>
                    <h3>Value</h3>
                    <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('debug_print_var')($_smarty_tpl->getValue('vars')['value'],10,80);?>

                </td>
                <td>
                    <?php if ((null !== ($_smarty_tpl->getValue('vars')['attributes'] ?? null))) {?>
                        <h3>Attributes</h3>
                        <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('debug_print_var')($_smarty_tpl->getValue('vars')['attributes']);?>

                    <?php }?>
                </td>
            </tr>
         <?php
$_smarty_tpl->setVariable('vars', $foreach1Backup);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
    </table>

    <h2>assigned config file variables</h2>

    <table id="table_config_vars">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('config_vars'), 'vars');
$foreach2DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('vars')->key => $_smarty_tpl->getVariable('vars')->value) {
$foreach2DoElse = false;
$foreach2Backup = clone $_smarty_tpl->getVariable('vars');
?>
            <tr>
                <td>
                    <h3 style="color: blue;">#<?php echo htmlspecialchars((string) ($_smarty_tpl->getVariable('vars')->key), ENT_QUOTES, 'UTF-8');?>
#</h3>
                    <?php if ((null !== ($_smarty_tpl->getValue('vars')['scope'] ?? null))) {?><strong>Origin:</strong> <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('debug_print_var')($_smarty_tpl->getValue('vars')['scope']);
}?>
                </td>
                <td>
                    <?php echo $_smarty_tpl->getSmarty()->getModifierCallback('debug_print_var')($_smarty_tpl->getValue('vars')['value'],10,80);?>

                </td>
            </tr>
        <?php
$_smarty_tpl->setVariable('vars', $foreach2Backup);
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

    </table>
    </body>
    </html>
<?php $_smarty_tpl->getSmarty()->getRuntime('Capture')->close($_smarty_tpl);
echo '<script'; ?>
>
    _smarty_console = window.open("", "console<?php echo htmlspecialchars((string) ($_smarty_tpl->getValue('targetWindow')), ENT_QUOTES, 'UTF-8');?>
", "width=1024,height=600,left=<?php echo htmlspecialchars((string) ($_smarty_tpl->getValue('offset')), ENT_QUOTES, 'UTF-8');?>
,top=<?php echo htmlspecialchars((string) ($_smarty_tpl->getValue('offset')), ENT_QUOTES, 'UTF-8');?>
,resizable,scrollbars=yes");
    _smarty_console.document.write("<?php echo strtr((string)$_smarty_tpl->getValue('debug_output'), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", 
						"\n" => "\\n", "</" => "<\/", "<!--" => "<\!--", "<s" => "<\s", "<S" => "<\S",
						"`" => "\\`", "\${" => "\\\$\{"));?>
");
    _smarty_console.document.close();
<?php echo '</script'; ?>
>
<?php }
}

<?php if (!empty($persoon_model) && $persoon_model != 'Klant'): ?>
    <?php return; ?>
<?php endif; ?>
<fieldset >
    <legend>Diensten</legend>
    <table cellpadding="0" cellspacing="0">
        <?php if (!empty($diensten)): ?>
            <?php foreach ($diensten as $dienst): ?>
                <?php
                    $value = $dienst['value'];
                    if ($dienst['type'] == 'date'):
                        $value = "";
                        if (empty($dienst['to'])):
                            $value.= "sinds ";
                        endif;
                        $value .= $date->show($dienst['from'], array('short'=>true))." ";
                        if (! empty($dienst['to'])):
                            $value .= "tot ";
                            $value .= $date->show($dienst['to'], array('short'=>true))." ";
                        endif;
                    endif;
                    $link = $dienst['name'];
                    if (! empty($dienst['url'])):
                        $link = $this->Html->link($dienst['name'], $dienst['url']);
                    endif;
                ?>
                <tr>
                    <td><?= $link ?></td>
                    <td><?= $value ?></td>
                </tr>
             <?php endforeach; ?>
         <?php else: ?>
             <?php __('Geen diensten'); ?>
         <?php endif; ?>
    </table>
</fieldset>

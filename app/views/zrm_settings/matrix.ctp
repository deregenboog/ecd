<table>
    <thead>
        <tr>
        <th>Domein</th>
            <?php foreach ($zrmData['zrm_items'] as $k => $v): ?>
                <?= "<th> ".$v."</th>" ?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
            echo $this->Form->create('ZrmSetting');
            $cnt = 0;

            foreach ($zrm_settings as $data) {
                $module_name = $data['ZrmSetting']['request_module'];
                if (isset($zrmData['zrm_names'][$module_name])) {
                    $module_name = $zrmData['zrm_names'][$module_name];
                }
                echo "<tr><td>".$this->Form->input('ZrmSetting.'.$cnt.'.id', array('type'=>'hidden', 'value' => $data['ZrmSetting']['id']));
                echo $module_name."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.inkomen', array('label' => '', 'checked' => $data['ZrmSetting']['inkomen']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.dagbesteding', array('label' => '', 'checked' => $data['ZrmSetting']['dagbesteding']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.huisvesting', array('label' => '', 'checked' => $data['ZrmSetting']['huisvesting']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.gezinsrelaties', array('label' => '', 'checked' => $data['ZrmSetting']['gezinsrelaties']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.geestelijke_gezondheid', array('label' => '', 'checked' => $data['ZrmSetting']['geestelijke_gezondheid']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.fysieke_gezondheid', array('label' => '', 'checked' => $data['ZrmSetting']['fysieke_gezondheid']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.verslaving', array('label' => '', 'checked' => $data['ZrmSetting']['verslaving']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.adl_vaardigheden', array('label' => '', 'checked' => $data['ZrmSetting']['adl_vaardigheden']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.sociaal_netwerk', array('label' => '', 'checked' => $data['ZrmSetting']['sociaal_netwerk']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.maatschappelijke_participatie', array('label' => '', 'checked' => $data['ZrmSetting']['maatschappelijke_participatie']))."</td>";
                echo "<td>".$this->Form->input('ZrmSetting.'.$cnt.'.justitie', array('label' => '', 'checked' => $data['ZrmSetting']['justitie']))."</td></tr>";
                $cnt++;
            }
        ?>
    </tbody>
</table>

<?= $this->Form->end(__('Submit', true)) ?>

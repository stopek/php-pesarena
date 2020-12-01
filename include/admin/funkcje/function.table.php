<?


class createTable
{
    public $cName;
    public $header;
    public $headClass;
    public $body;
    public $colorId;
    public $legend;
    public $clTableID;


    private $tableColorClass_1 = "tableColorA";
    private $tableColorClass_2 = "tableColorB";


    public function __construct($clName, $legend = null, $clTableID = null)
    {
        $this->cName = $clName;
        $this->colorId = 0;
        $this->legend = $legend;
        $this->clTableID = $clTableID;
    }

    public function setTableHead($arrHead, $headClass)
    {
        $this->header = $arrHead;
        $this->headClass = $headClass;
    }

    public function setTableBody($arrBody)
    {
        $this->body = $arrBody;
    }

    public function getTable()
    {
        $ret = $this->startTable();
        $ret .= $this->createHeader();
        $ret .= $this->createBody();
        $ret .= $this->endTable();

        return $ret;
    }

    public function startTable()
    {
        return "
		<fieldset><legend>{$this->legend}</legend>
			<table class=\"{$this->cName}\"  frame=\"void\"  id=\"{$this->clTableID}\">";
    }

    public function createHeader()
    {
        $ret = "
				<thead class=\"{$this->headClass}\">
					<tr>";
        foreach ($this->header as $value) {
            $ret .= "
						<th " . (is_array($value) ? " title = \"{$value['title']}\" colspan=\"{$value['cols']}\" id = \"{$value['id']}\" class=\"{$value['leagueTdStyle']}\" " : null) . ">" . (is_array($value) ? $value['value'] : $value) . "</th>";
        }
        $ret .= "
					</tr>
				</thead>";
        return $ret;
    }

    public function createBody()
    {
        $ret = "
				<tbody>";
        if (count($this->body) != 0) {
            foreach ($this->body as $id) {
                $ret .= "
					<tr class=\"{$this->getTableColor()} " . (defined('PANE') ? " pane" : null) . "\">";
                foreach ($id as $value) {
                    $ret .= "
						<td " . (is_array($value) ? "colspan=\"{$value['cols']}\" id = \"{$value['id']}\" class=\"{$value['leagueTdStyle']}\" " : null) . ">" . (is_array($value) ? $value['value'] : $value) . "</td>";
                }
                $ret .= "
					</tr>";
                $this->colorId++;
            }
        } else {
            $ret .= "<div class=\"error\"><img src=\"img/error.png\" alt=\"\"/><span>" . admsg_tableIsEmtpy . "</span></div>";
        }
        $ret .= "
				</tbody>";
        return $ret;
    }

    private function getTableColor()
    {
        return ($this->colorId % 2 == 0 ? $this->tableColorClass_1 : $this->tableColorClass_2);
    }

    public function endTable()
    {
        return "
			</table>
		</fieldset>
		";
    }

}


?>
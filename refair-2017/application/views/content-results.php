 foreach($this->weighted as $item){
            echo "<div class=\"jumbotron\">";
            echo " <a class=\"outbounda\" href=\"".$item['4']."\" target=\"_blank\">".$item['3']."</a><br/>";
            echo "[<div class=\"progress-bar progress-bar-success\"> style=";
            for ($i = 0; ($i<$item['0']) && ($i<70); $i++){echo "=";}
            echo "</a>] ".$item['0']." <b>".$kwone."</b> <br/>";
            echo "[<a class=\"green\">";
            for ($i = 0; $i<$item['1'] && ($i<70); $i++){echo "=";}
            echo "</a>] ".$item['1']." <b>".$kwtwo."</b> <br/>";
            echo "[<a class=\"blue\">";
            for ($i = 0; $i<$item['2'] && ($i<70); $i++){echo "=";}
            echo "</a>] ".$item['2']." <b>".$kwthree."</b> <br/>";
            echo "<a class=\"ital\"".$item['5']."</a><br/></div>";
        }

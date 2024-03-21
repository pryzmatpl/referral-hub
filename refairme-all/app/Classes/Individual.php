<?php
namespace App;
namespace App\Classes;
use App\Classes\Fitnesscalc;

class Individual {
    public $genes = array();  //defines an empty  array of genes arbitrary length
    public $fitness = 0;
	// Cache
	
	public function random() {
        return (float)rand()/(float)getrandmax();
	}

    // Create a random individual
    public function generateIndividual($size,$fourcolumn) {
        //We generate a population based on randomization of counts
		//now lets randomly load the genes (array of ascii characters)	 to the size of the array
        $it = 0;
        foreach($fourcolumn as $item){
            $item['quantity'] =  rand(1, 3*$item['quantity']);
            $this->setGene($it,$item);
            $it++;
        }
    }
    
    /* Getters and setters */
    // Use this if you want to create individuals with different gene lengths
    public function getGene($index) {
        return $this->genes[$index];
    }

    public function setGene($index,$value) {
        //Array of (var_id, prd_id, quant, price) is vlaue
        $this->genes[$index] = $value;
        $this->fitness = $value['price']*$value['quantity']/($value['quantity']+$value['price']);
    }

    /* Public methods */
    public function size() {
        return count($this->genes);
    }

    public function getFitness() {
        if ($this->fitness == 0) {
            $this->fitness = FitnessCalc::getFitness($this);  //call static method to calculate fitness
        }
        return $this->fitness;
    }

    public function __toString() {
        $geneString = null;
        for ($i = 0; $i <  count($this->genes); $i++) {
            $geneString .= $this->getGene($i);
        }
		print_r($geneString);
        return (string)$geneString;
    }
}

?>
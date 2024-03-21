    public function secure($hhash,$site='')
    {
        if(!strcmp($site,'')){
            $hash=base64_decode($hhash);
            $secarr=explode('~',$hash);
            $this->emailes=$secarr[0];

            $usuario = $this->user_model->get_by('email',$secarr[0]);
            
            $CI = & get_instance();
            $CI->active = password_verify($secarr[2],$secarr[1]);
            $CI->layout="auth";
            
            $this->styles = array("style");
            $this->menus = array("Refair/secure/".$hhash."~Refair",
                                 "Account/secure/".$hhash."~Dashboard",
                                 "Refair/secure/".$hhash."/jobs~All Jobs",
                                 "Account/secure/".$hhash."/profile~Your Profile",
                                 "Refair/secure/".$hhash."/describeimports/".$hhash."~Your LinkedIn imports",
                                 "Refair/secure/".$hhash."/referrals~Your Referrals",
                                 "Auth/logout~Log out");

            $this->dataJobs = $this->jobdesc_model->get_many_by("poster_id",$usuario->email);
            $this->dataReferrals = $this->jobref_model->get_many_by('referrer_id',$usuario->email);
            $this->dataReferralsReferred = $this->jobref_model->get_many_by('referred_id',$this->emailes);
                        
            $this->dataLocations = $this->location_model->get_many_by('userid',$usuario->email);

            $this->userAble = true; //we are in the accounts dashboard we see all
            
            $this->hhhash=$hhash;
            $this->pasdat = array($this->dataReferralsReferred, $this->userAble, $this->menus,$this->emailes,$this->dataJobs,$this->hhhash,$this->dataReferrals,$this->dataLocations);
            
            $this->session->set_flashdata('message', array("Your control pod for Prism, dear <a id=\"emailuid\" name=\"emailuid\">".$this->emailes."</a>. You agree to cookies."));
            
            $this->parts[TITLE] = $this->load->view('title-refair',$this->pasdat,true);
            $this->parts[HEAD] = $this->load->view('header-refair',$this->pasdat,true);
            $this->parts[SCRIPTS] = $this->load->view('scripts-refair',$this->pasdat,true);
            $this->parts[META] = $this->load->view('metas',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[CONTENTE] = $this->load->view('refair-user',$this->pasdat,true);
            $this->parts[REFAIRJOBS] = $this->load->view('refair-userjobs',$this->pasdat,true);
            $this->parts[REFERRALS] = $this->load->view('refair-referrals',$this->pasdat,true);
            $this->parts[LOCATIONS] = $this->load->view('refair-locations',$this->pasdat,true);
            $this->parts[COOKIE] = $this->load->view('cookie',$this->pasdat,true);
            $this->parts[QUOTES] = $this->load->view('quotes',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[MODALREFER] = $this->load->view('modal-refer',$this->pasdat,true); 
            $this->parts[MODALADDLOCATION] = $this->load->view('modal-addlocation',$this->pasdat,true);
            $this->parts[MODALDELETEREF] = $this->load->view('modal-deleteref',$this->pasdat,true);
            $this->parts[MODALDELETELOCATION] = $this->load->view('modal-deletelocation',$this->pasdat,true);
            $this->parts[MODALDELETE] = $this->load->view('modal-delete',$this->pasdat,true);
            $this->parts[MODALAPPLY] = $this->load->view('modal-apply',$this->pasdat,true);
            $this->parts[WAITING] = $this->load->view('waiting',$this->pasdat,true);
            $this->parts[FLASH] = $this->load->view('flash',$this->pasdat,true);
        }else{
            $hash=base64_decode($hhash);
            $secarr=explode('~',$hash);
            $email=$secarr[0];
            $usuario = $this->user_model->get_by('email',$secarr[0]);
            
            $CI = & get_instance();
            $CI->active = password_verify($secarr[2],$secarr[1]);
            $CI->layout="auth";
            
            $this->styles = array("style");
            $this->menus = array("#about~About us",
                                 "#contact~Contact us",
                                 "Refair/secure/".$hhash."~Refair",
                                 "Account/secure/".$hhash."~Dashboard");
            $this->lowermenus = array("#about~About us",
                                      "#contact~Contact us",
                                      "Refair/secure/".$hhash."~Refair",
                                      "Account/secure/".$hhash."~Dashboard");
        
            $this->pasdat = array($this->menus,$this->lowermenus);
            $CI = & get_instance();
        
            $this->session->set_flashdata('message', array("Hello, user <a id=\"emailuid\" name=\"emailuid\">NULL</a>. You agree to cookies."));
            
            switch($site){
            case 'network': {$this->network($hhash); break;}
            case 'profile': {$this->profile($hhash); break;}
            default: break;
            }
        }
    }

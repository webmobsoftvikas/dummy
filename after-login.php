<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Instimatch</title>
        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!-- Custom Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="datatables/dataTables.bootstrap.css">   
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-static-top nav-custome" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="logo"><img class="pull-left" width="80" height="80" src="images/logo.png" alt=""/>&nbsp;<a class="navbar-brand" href="index.html">INSTIMATCH</a></div> 
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"> 

                    <ul class="nav navbar-nav navbar-right nav-icon">
                        <li>                            
                            <a href="" data-toggle="modal" data-target="#updateProfile"><img src="images/user1.png" alt=""/></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle"><img src="images/noti.png" alt=""/><span class="badge badge-danger">2</span></a>
                            <ul class="dropdown-menu" id="menu1">
                                <li><a href="#">2-level Menu2-level Menu2-level Menu2-level Menu2-level Menu2-level Menu2-level Menu<i class="pull-right fa fa-check"></i></a></li>
                                <li><a href="#">Another action <i class="pull-right fa fa-check"></i></a></li>
                                <li><a href="#">Something else here <i class="pull-right fa fa-check"></i></a></li>
                                <li class="divider"></li>
                                <li><a href="#" class="text-center">Separated link </a></li>
                            </ul>
                        </li>                     
                        <li>
                            <a href="login.php"><img src="images/chat.png" alt=""/></a>                            
                        </li>
                        <li>                            
                            <a href="after-login.php"><img src="images/cross.png" alt=""/></a>
                        </li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right nav-language">
                        <li><a class="" href="">DE</a></li>
                        <li><a class="" href="">FR</a></li>
                        <li><a class="selected" href="">EN</a></li>
                    </ul>

                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <!--update profile Start -->
        <!-- Modal -->
        <div class="modal fade " id="updateProfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title h2" id="myModalLabel">Update Profile</h4>
                    </div>
                    <div class="modal-body color-black">
                        <div class="h3 c-light-blue">Edit Details</div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="form-group col-md-6">
                                    <label class="control-label">First Name</label>
                                    <input type="text" value="Pete" name="fname" class="form-control">                                            
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Last Name</label>
                                    <input type="text" value="k" name="lname" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label class="control-label">Company Name</label>
                                    <input type="text" value="Axis" name="company_name" class="form-control">                                            
                                </div>                                  
                                <div class="form-group col-md-12">
                                    <button class="btn btn-primary btn-lg pull-right" type="submit">Update</button>   
                                </div>                                  
                            </div>                            
                        </div>
                        <div class="h3 c-light-blue">Change Password</div>
                        <hr>
                        <div class="row">                            
                            <div class="col-md-12 col-xs-12">
                                <div class=" col-md-6">
                                    <label class="control-label">Old Password</label>
                                    <input type="password" id="password" name="pwd" placeholder="" class="form-control">
                                </div>
                                <div class=" col-md-6">
                                    <label class="control-label">New Password</label>
                                    <input type="password" id="new_pwd" name="new_pwd" placeholder="" class="form-control">
                                </div>
                                <div class="col-md-12 top-buffer">
                                    <label class="control-label">Confirm Password</label>
                                    <input type="password" id="confirm_pwd" name="confirm_pwd" placeholder="" class="form-control">
                                 </div>
                                <div class="col-md-12 top-buffer">
                                    <button class="btn btn-primary btn-lg pull-right" id="btn_update" type="submit">Update</button>
                                </div>
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--update profile END -->
        <!--bid pop up Start -->
        <!-- Modal -->
        <div class="modal fade " id="bidpupup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content  ">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Fill your minimum bid</h4>
                    </div>
                    <div class="modal-body color-black">
                        <div class="row form-inline">
                            <div style="text-align:center; color:red;" id="min_bid_error" class="error_strings col-md-12"></div>
                            <div class="form-group col-md-8 col-sm-8 col-xs-8">
                                <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="exampleInputAmount" placeholder="Amount">
                                    <div class="input-group-addon">%</div>
                                </div>
                            </div>
                            <div class="form-group col-md-4"><button class="btn btn-default" id="but" type="button">Select</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--END bid pop up here -->
        <!--Call out detail popup start-->
        <div class="modal fade " id="calloutDetailPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog " role="document">
                <div class="modal-content  ">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">CallOut Details</h4>
                    </div>
                    <div class="modal-body color-black">
                        <div class="row">
                            <div class="col-md-11">
                                <label>Pensionskassenverbund Aargau</label>                               
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox"  value="option1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11">Pensionskassenverbund Aargau                              
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox"  value="option1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11">Project_Finance_Lender                              
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox"  value="option1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Call out detail popup End-->
        <!--closed request archive detail popup start-->
        <div class="modal fade " id="myClosedRequestedLoan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content  ">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Archived Requests</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table table-responsive">
                            <table class="table table-responsive  table-bordered" id="myRequest3" cellspacing="0" width="100%" >
                                <thead>
                                    <tr>
                                        <th><abbr title="Name of Borrower" class="initialism">Borrower</abbr></th>
                                        <th><abbr title="Amount  In Thousand" class="initialism">Amount</abbr></th>
                                        <th><abbr title="Amount" class="initialism">Amount</abbr></th>
                                        <th><abbr title="Term" class="initialism">Term</abbr></th>
                                        <th><abbr title="Maturity" class="initialism">Maturity</abbr></th>
                                        <th><abbr title="Interest Scheduled" class="initialism">Interest</abbr></th>
                                        <th><abbr title="Status" class="initialism">Status</abbr></th>
                                        <th><abbr title="Request Close" class="initialism">Request</abbr></th>
                                        <th><abbr title="Number of Offers" class="initialism">Offers</abbr></th>
                                        <th><abbr title="Best Offers" class="initialism">B-Offers</abbr></th>
                                        <th><abbr title="Notes" class="initialism">Notes</abbr></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="" id="">dav</a></td>
                                        <td><span>3</span></td>
                                        <td>3 mio</td>
                                        <td>3M</td>
                                        <td>05/04/2016</td>
                                        <td>Monthly</td>
                                        <td class="c-green">Open</td>
                                        <td>05/01/2016 11:00am</td>
                                        <td>0</td>
                                        <td>-</td>
                                        <td><img src=""></td>
                                    </tr>
                                    <tr>
                                        <td><a href="" id="">aav</a></td>
                                        <td><span>3</span></td>
                                        <td>3 mio</td>
                                        <td>3M</td>
                                        <td>05/04/2016</td>
                                        <td>Monthly</td>
                                        <td>Open</td>
                                        <td>05/01/2016 11:00am</td>
                                        <td>0</td>
                                        <td>-</td>
                                        <td><img src=""></td>
                                    </tr>
                                    <tr>
                                        <td><a href="" id="">bav</a></td>
                                        <td><span>3</span></td>
                                        <td>3 mio</td>
                                        <td>3M</td>
                                        <td>05/04/2016</td>
                                        <td>Monthly</td>
                                        <td>Open</td>
                                        <td>05/01/2016 11:00am</td>
                                        <td>0</td>
                                        <td>-</td>
                                        <td><img src=""></td>
                                    </tr>
                                    <tr>
                                        <td><a href="" id="">eav</a></td>
                                        <td><span>3</span></td>
                                        <td>3 mio</td>
                                        <td>3M</td>
                                        <td>05/04/2016</td>
                                        <td>Monthly</td>
                                        <td>Open</td>
                                        <td>05/01/2016 11:00am</td>
                                        <td>0</td>
                                        <td>-</td>
                                        <td><img src=""></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--closed request archive detail detail popup End-->
        <!--Loan Detail popup start-->
        <div class="modal fade " id="loanDetailPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content  ">
                    <div class="modal-header modal-header-info">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Loan Details</h4>
                    </div>
                    <div class="modal-body color-black">
                        <div class="row padding-10">
                            <div class="col-md-12">
                                <b class="c-light-blue">Request Detail:</b><span>demo1</span>
                            </div>

                            <div class="table table-responsive">
                                <table class="table table-responsive table-bordered" id="loanDetail">                              
                                    <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Maturity</th>
                                            <th>Interest Scheduled</th>
                                            <th>Status</th>
                                            <th>Closing Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                        <tr>
                                            <td>122 mio</td>
                                            <td>07/04/2016</td>
                                            <td>Monthly</td>
                                            <td>Open</td>
                                            <td>07/01/2016 11:00am</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <b class="c-light-blue">My Notes</b>
                                </div>
                                <div class="col-md-12">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                                </div>
                                <div class="col-md-12">
                                    <span class=" bg-primary btn-padding">OFFERTE LISTE</span>
                                </div>
                                <div class="col-md-12 top-buffer">
                                    <span class="label label-default btn-padding">Top 5</span>
                                    <span class="label label-default btn-padding">Auktion Ende 07/01/2016 11:00am</span>
                                    <span class="label label-default btn-padding">List Printen</span>
                                </div>

                            </div>
                            <div class="table table-responsive">
                                <table class="table table-responsive table-bordered">                              
                                    <thead>
                                        <tr>
                                            <th>Offer Number</th>
                                            <th>Company Name</th>
                                            <th>Offered Amount</th>
                                            <th>Date Offered</th>
                                            <th>Rate%</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Axis</td>
                                            <td>122 mio</td>
                                            <td>08/01/2016</td>
                                            <td>45</td>
                                            <td><input type="button" class="btn btn-success" value="Accept"/></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <input type="submit" class="btn btn-primary b-orange"  value="Ammend">
                                    <input type="submit" class="btn btn-primary b-red"  value="Withdraw">
                                    <a class="btn btn-info" target="_blank" href="">Print Report</a>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Loan detail popup End-->
        <!-- Page Content -->
        <div class="container-fluid">
            <!-- Marketing Icons Section -->
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="h4 c-light-blue">New Request</div>
                    <div class="form newRequest-form">
                        <form role="form" class="">
                            <div class="form-group col-md-12">
                                <label for="email">Project</label>
                                <input type="email" class="form-control input-login" id="email">
                            </div>
                            <div class="form-group col-md-6  col-xs-5">
                                <label>Amount</label>
                            </div>
                            <div class="form-group  col-md-6 text-center">                                   
                                <span class="btn btn-sm btn-default selected">CHF</span>
                                <span class="btn btn-sm btn-default">EUR</span>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="amount" class="form-control input-login" id="amt">
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control input-login" id="money_text" name="money_text">
                                    <option vaue="thousand">Thousand</option>
                                    <option selected="" vaue="million">mio</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Maturity</label>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control input-login" name="maturityMonths" id="maturityMonths">
                                    <option value="3M" data-val="3" data-type="m">3M</option>
                                    <option value="6M" data-val="6" data-type="m">6M</option>
                                    <option value="12M" data-val="12" data-type="m">12M</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="amount" class="form-control input-login" id="amt">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Interest Scheduled</label>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control input-login" name="interest_scheduled" id="interest_scheduled">
                                    <option value="Monthly" selected=""> Monthly</option>
                                    <option value="Quarterly"> quarterly</option>
                                    <option value="Semi Annual"> semi annual</option>
                                    <option value="Yearly"> yearly</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Anonymous</label>
                                <div class="onoffswitch pull-right">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                                    <label class="onoffswitch-label" for="myonoffswitch">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-md-7">
                                <lable>Closing Date</lable>
                                <input type="amount" class="form-control input-login" id="amt">
                            </div>
                            <div class="form-group col-md-5">
                                <lable>Closing Time</lable>
                                <select id="close_time" name="close_time" class="form-control input-login">
                                    <option value="11:00am">11:00am</option>
                                    <option value="12:00pm">12:00pm</option>
                                    <option value="2:00pm">2:00pm</option>
                                    <option value="3:00pm">3:00pm</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <lable>Immediate Execution</lable>                                    
                            </div>
                            <div class="col-md-6">
                                <div class="onoffswitch pull-right">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch2" checked>
                                    <label class="onoffswitch-label" for="myonoffswitch2">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>                                 
                            </div>
                            <div class="col-md-6 top-buffer">
                                <button class="btn btn-primary" type="button"  data-toggle="modal" data-target="#bidpupup">Enter Minimum Bid</button>
                            </div>
                            <div class="col-md-12 form-group top-buffer">
                                <label for="note">Notes</label>
                                <textarea class="form-control" rows="5" id="note"></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <button class="btn btn-info b-green" type="button" data-target="#calloutDetailPopup" data-toggle="modal">CallOut Details</button>
                            </div>
                            <div class="form-group col-md-6 text-center">
                                <input type="submit" value="Request" class="btn btn-info pull-right b-orange" >
                            </div>
                            <div class="form-group col-md-12">
                                <button  class="btn btn-info btn-block b-black" data-target="#myClosedRequestedLoan" data-toggle="modal"  type="button" >Archived Requests</button>
                            </div>

                        </form>
                    </div>
                </div>
                <!--right side table div my request for offer-->

                <div class="row col-lg-8 col-md-8 col-sm-8">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="h4 c-light-blue">My Requests For Offers</div>
                        <div class="table table-responsive">
                            <table class="table table-responsive  table-bordered" id="myRequest" cellspacing="0" width="100%" >
                                <thead>
                                    <tr>
                                        <th><abbr title="Name of Borrower" class="initialism">Borrower</abbr></th>
                                        <th><abbr title="Amount  In Thousand" class="initialism">Amount</abbr></th>
                                        <th><abbr title="Amount" class="initialism">Amount</abbr></th>
                                        <th><abbr title="Term" class="initialism">Term</abbr></th>
                                        <th><abbr title="Maturity" class="initialism">Maturity</abbr></th>
                                        <th><abbr title="Interest Scheduled" class="initialism">Interest</abbr></th>
                                        <th><abbr title="Status" class="initialism">Status</abbr></th>
                                        <th><abbr title="Request Close" class="initialism">Request</abbr></th>
                                        <th><abbr title="Number of Offers" class="initialism">Offers</abbr></th>
                                        <th><abbr title="Best Offers" class="initialism">B-Offers</abbr></th>
                                        <th><abbr title="Notes" class="initialism">Notes</abbr></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="" id="" title="Click to View Loan Detail" data-target="#loanDetailPopup" data-toggle="modal">click here</a></td>
                                        <td><span>3</span></td>
                                        <td>3 mio</td>
                                        <td>3M</td>
                                        <td>05/04/2016</td>
                                        <td>Monthly</td>
                                        <td class="c-green">Open</td>
                                        <td>05/01/2016 11:00am</td>
                                        <td>0</td>
                                        <td>-</td>
                                        <td><img src=""></td>
                                    </tr>
                                    <tr>
                                        <td><a href="" id="">aav</a></td>
                                        <td><span>3</span></td>
                                        <td>3 mio</td>
                                        <td>3M</td>
                                        <td>05/04/2016</td>
                                        <td>Monthly</td>
                                        <td>Open</td>
                                        <td>05/01/2016 11:00am</td>
                                        <td>0</td>
                                        <td>-</td>
                                        <td><img src=""></td>
                                    </tr>
                                    <tr>
                                        <td><a href="" id="">bav</a></td>
                                        <td><span>3</span></td>
                                        <td>3 mio</td>
                                        <td>3M</td>
                                        <td>05/04/2016</td>
                                        <td>Monthly</td>
                                        <td>Open</td>
                                        <td>05/01/2016 11:00am</td>
                                        <td>0</td>
                                        <td>-</td>
                                        <td><img src=""></td>
                                    </tr>
                                    <tr>
                                        <td><a href="" id="">eav</a></td>
                                        <td><span>3</span></td>
                                        <td>3 mio</td>
                                        <td>3M</td>
                                        <td>05/04/2016</td>
                                        <td>Monthly</td>
                                        <td>Open</td>
                                        <td>05/01/2016 11:00am</td>
                                        <td>0</td>
                                        <td>-</td>
                                        <td><img src=""></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row w-black"><div class="col-md-3">                        
                            </div>
                            <div class="col-md-6  text-center">
                                <a href="" class="btn btn-link btn-lg">&lt;&lt; Previous</a>
                                <a href="" class="btn btn-link btn-lg">Next &gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="h4 c-green">My Requests For Offers</div>
                        <div class="table table-responsive">
                            <table id="myRequest2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>Edinburgh</td>
                                        <td>61</td>
                                        <td>2011/04/25</td>
                                        <td>$320,800</td>
                                    </tr>
                                    <tr>
                                        <td>Garrett Winters</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                        <td>63</td>
                                        <td>2011/07/25</td>
                                        <td>$170,750</td>
                                    </tr>
                                    <tr>
                                        <td>Ashton Cox</td>
                                        <td>Junior Technical Author</td>
                                        <td>San Francisco</td>
                                        <td>66</td>
                                        <td>2009/01/12</td>
                                        <td>$86,000</td>
                                    </tr>
                                    <tr>
                                        <td>Cedric Kelly</td>
                                        <td>Senior Javascript Developer</td>
                                        <td>Edinburgh</td>
                                        <td>22</td>
                                        <td>2012/03/29</td>
                                        <td>$433,060</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="row w-black"><div class="col-md-3">                        
                            </div>
                            <div class="col-md-6  text-center">
                                <a href="" class="btn btn-link btn-lg">&lt;&lt; Previous</a>
                                <a href="" class="btn btn-link btn-lg">Next &gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="h4 c-green">My Requests For Offers</div>
                        <div class="table table-responsive">
                            <table id="myRequest4" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>Edinburgh</td>
                                        <td>61</td>
                                        <td>2011/04/25</td>
                                        <td>$320,800</td>
                                    </tr>
                                    <tr>
                                        <td>Garrett Winters</td>
                                        <td>Accountant</td>
                                        <td>Tokyo</td>
                                        <td>63</td>
                                        <td>2011/07/25</td>
                                        <td>$170,750</td>
                                    </tr>
                                    <tr>
                                        <td>Ashton Cox</td>
                                        <td>Junior Technical Author</td>
                                        <td>San Francisco</td>
                                        <td>66</td>
                                        <td>2009/01/12</td>
                                        <td>$86,000</td>
                                    </tr>
                                    <tr>
                                        <td>Cedric Kelly</td>
                                        <td>Senior Javascript Developer</td>
                                        <td>Edinburgh</td>
                                        <td>22</td>
                                        <td>2012/03/29</td>
                                        <td>$433,060</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="row w-black"><div class="col-md-3">                        
                            </div>
                            <div class="col-md-6  text-center">
                                <a href="" class="btn btn-link btn-lg">&lt;&lt; Previous</a>
                                <a href="" class="btn btn-link btn-lg">Next &gt;&gt;</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
            <hr>
            <!-- Footer -->
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p>Copyright &copy; Instimatch 2014</p>
                    </div>
                </div>
            </footer>
        </div>
        <!-- /.container -->


        <!-- jQuery -->
        <script src="js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
        <script src="datatables/jquery.dataTables.min.js"></script>
        <script src="datatables/dataTables.bootstrap.min.js"></script>

        <script>
                                                    $('#bidpopup').modal();
        </script>
        <script>
            $("#myRequest").DataTable({
                "order": [[1, "asc"]],
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false
            });
            $("#myRequest2").DataTable({
                "order": [[1, "asc"]],
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false
            });
            $("#myRequest3").DataTable({
                "order": [[1, "asc"]],
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false
            });
            $("#myRequest4").DataTable({
                "order": [[1, "asc"]],
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false
            });
            $("#loanDetail").DataTable({
                "order": [[1, "asc"]],
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false
            });
        </script>
    </body>

</html>

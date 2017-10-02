firebase.auth().onAuthStateChanged(function(user) {
    if(user && user.emailVerified){
        var email = user.email;
        console.log(email + ' logged in');
        $('#logout').removeClass('hidden');
    }else if (user && !user.emailVerified){
        //TODO: Give the user the option to resend their email.
        
        alert('Please validate your email by visiting the link in your email.');
    }
    else{
        //kick the user to the login page
        window.location.replace("login.html");
        console.log('not logged in.  Redirect');
        $('#logout').addClass('hidden');
    }
});

/*TODO: 

* prepopulate Contact info
* Clean up BOP stack format
* add review info field
* show diagram of riser gap, HPU elevation, Subsea depth
* add units
* Add shear ram to BOP type.
* If shear ram is selected.  Allow user to select predefined BOP types.
* Allow user to upload multiple files http://www.alpacajs.org/docs/fields/upload.html and https://github.com/blueimp/jQuery-File-Upload/wiki
* Allow user to request shear or accumulator calcs
* Allow user to load previous form.
* Allow user to delegate section.
* 
*/  
 $(document).ready(function() {
     var database = firebase.database();
    
    $('#logout').click(function() {
        firebase.auth().signOut();
    });
	
	//TODO: error - formdata object can't be set in OnAuthState change because .alpaca() executes first.  The object is never updated.

    $("#form_info").alpaca({
        "optionsSource": "./cids_options1.json",
        "schemaSource": "./cids_schema1.json",
        "dataSource": "./cids_data1.json",
        //"data":  formdata,
        "options":{
            "fields": {
                "email2":{
                    "validator": function(callback) {
                        var value2 = this.getValue();
                            value1 = this.getParent().childrenByPropertyId.email.getValue();
                        if (value2 == value1){
                            callback({
                                "status": true,
                                "message": "Words match"
                            });
                        } else{
                            callback({
                                "status": false,
                                "message": "Emails do not match"
                            });
                        }
                    }
                }
            }
        },
        "view": {
            "parent": "bootstrap-edit-horizontal",
            "wizard": {
                "title":"Welcome to the Wizard",
                "description": "Fill it out",
                "bindings": {
                    "name": 1,
                    "company": 1,
                    "phone": 1,
                    "email": 1,
                    "email2": 1,
                    "rigName": 2,
                    "rigOwner": 2,
                    "bopLocation": 2,
                    "heightRiser": 2,
                    "heightHPU": 2,
                    "BOP_Stack": 3,
                    "wellName": 4,
                    "wellLocation": 4,
                    "MAWHP": 4,
                    "MASP": 4,
                    "depth": 4,
                    "mudWeight": 4,
                    "tubulars": 5,
                    "comments": 6,
                    "files": 6,
                    "review": 7                             
                },
                "steps": [{
                    "title": "Operator",
                    "description": "Contact information"
                }, {
                    "title": "Rig Info"
                }, {
                    "title": "BOP",
                    "description": "Stack Configuation"
                }, {
                    "title": "Well Parameters"
                }, {
                    "title": "Tubulars"
                }, {
                    "title": "Comments"
                }, {
                    "title": "Submit"
                }],
                "buttons": {
                    "submit": {
                        "title": "Submit!",
                        "validate": function(callback) {
	                        console.log("Submit validate()");
	                        callback(true);
	                    },
                        "click": function() {
                            //TODO: disable button on submit
                            alert(JSON.stringify(this.getValue()));
                            
                            //TODO:, check for the last id, then increment this to the next.  Store under submittedCIDS/{newID}
                            //const dbRefSubmitted = database.ref().child('submittedCIDS/lastID');
                            //dbRefSubmitted.once('value', snapshot => console.log(snapshot.val()));
                            
                            //send to firebase
                            var timestamp = Date.now();
                            database.ref('submittedCIDS/' + timestamp).set(this.getValue());
                            
                            //send to mailer
                            //javascript works, but using jquery instead
                            //request= new XMLHttpRequest()
    						//request.open("POST", "scripts/cid_submit_mailer.php", true);
    						//request.setRequestHeader("Content-type", "application/json");
    						//request.send(JSON.stringify(this.getValue()));
							
							//jquery
							$.ajax({url: "scripts/cid_submit_mailer.php",
							type: "application/json",
							data:  JSON.stringify(this.getValue()),
							type: "POST",
							context: document.body})
							.done(function(){console.log("Mail Sent");})
							.fail(function(){console.log("Mailer failed");});
							
							
                            //TODO: send success modal.
                            }
                        }
                     }
                 }
            }
    });
});
// From the class library
(function(){Date.prototype.deltaDays=function(c){return new Date(this.getFullYear(),this.getMonth(),this.getDate()+c)};Date.prototype.getSunday=function(){return this.deltaDays(-1*this.getDay())}})();
function Week(c){this.sunday=c.getSunday();this.nextWeek=function(){return new Week(this.sunday.deltaDays(7))};this.prevWeek=function(){return new Week(this.sunday.deltaDays(-7))};this.contains=function(b){return this.sunday.valueOf()===b.getSunday().valueOf()};this.getDates=function(){for(var b=[],a=0;7>a;a++)b.push(this.sunday.deltaDays(a));return b}}
function Month(c,b){this.year=c;this.month=b;this.nextMonth=function(){return new Month(c+Math.floor((b+1)/12),(b+1)%12)};this.prevMonth=function(){return new Month(c+Math.floor((b-1)/12),(b+11)%12)};this.getDateObject=function(a){return new Date(this.year,this.month,a)};this.getWeeks=function(){var a=this.getDateObject(1),b=this.nextMonth().getDateObject(0),c=[],a=new Week(a);for(c.push(a);!a.contains(b);)a=a.nextWeek(),c.push(a);return c}};

// Global variables to maintain information
let usr = "";
let isLoggedIn = false;
let date = new Date();
let month_number = date.getMonth();
let year = date.getFullYear();
let month = new Month(year, month_number);
let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

// Check if user is connected and display the appropriate screen
function display() {
    if (!isLoggedIn) {
        document.getElementsByClassName("welcome")[0].style.display = "initial";
        document.getElementsByClassName("app")[0].style.display = "none";

    } else {
        document.getElementsByClassName("welcome")[0].style.display = "none";
        document.getElementsByClassName("app")[0].style.display = "initial";
        document.getElementById("welcome_msg").textContent = "Welcome " + usr;
        displayMonthHeader(months[month.month], year);
        displayCalendar(month);
    }
}

// Ajax login
function loginAjax() {

    const usr = document.getElementById("l_usr").value;
    const pwd = document.getElementById("l_pwd").value;

    // Make a URL-encoded string for passing POST data:
    const data = { 'usr': usr, 'pwd': pwd };

    fetch("login.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })

        
        .then(response => response.json())
        .then(res => {if (res.success){
                //$("#token").val(res.token);
                document.getElementById("welcome_msg").textContent = "Welcome " + res.usr;

                console.log("You've been logged in!");
                isLoggedIn = true;
                display();
            } else {
                console.log(res.message);
            }
        })
        .catch(err => console.error(err));
}

// Check if a session is started and update the appropriate variables
function loginCheckAjax() {
    const data = { 'usr': usr}
    fetch("checkLogin.php")
    .then(response => response.json())
    .then(res => {
        if (res.success){
            usr = res.usr;
            isLoggedIn = true;
            display();
        } else {
            usr = "";
            isLoggedIn = false;
        }
    })
}

// Ajax Sign Up
function signupAjax() {
    
    const usr = document.getElementById("s_usr").value;
    const pwd1 = document.getElementById("s_pwd1").value;
    const pwd2 = document.getElementById("s_pwd2").value;

    if(pwd1 == pwd2){
        const data = { 'usr': usr, 'pwd': pwd1 };

        fetch("signup.php", {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'content-type': 'application/json' }
            })
            .then(response => response.json())
            .then(res => {if (res.success){
                    //$("#token").val(res.token);
                    document.getElementById("welcome_msg").textContent = "Welcome " + res.usr;

                    console.log("You've been logged in!");
                    isLoggedIn = true;
                    display();
                } else {
                    console.log(res.message);
                }
            })
            .catch(err => console.error(err));
    } else {
        console.log("The passwords don't match!!");
    }
}

// log the user out and show the authentication screen
function logoutAjax() {
    fetch("logout.php");
    //$('#token').val("");
    console.log("You've been logged out!");
    isLoggedin = false;
    username = "";
    document.getElementsByClassName("welcome")[0].style.display = "initial";
    document.getElementsByClassName("app")[0].style.display = "none";
}

// Display the current month on the header of the calendar
function displayMonthHeader(month, year) {
    document.getElementById("this_month").innerHTML = "<h3>" + month + " " + year + "</h3>";
}

// assign a color to events according to their categories
function eventColor(category) {
    let color;
    switch(category){
        case "":
            color = "white";
            break;
        case "work":
            color = "yellow";
            break;
        case "home":
            color = "lightblue";
            break;
        case "school":
            color = "lightgreen";
            break;
        case "sports":
            color = "orange";
            break;
        default:
            color = "white";
            break;
    }
    return color;
}

// display an event
function displayEvent(event, day) {
    let eventId = event.eventid;
    let title = event.title;
    let description = event.description;
    let date = event.date;
    let time = event.time;
    let isPublic = event.is_public;
    let public = "public";
    if(!isPublic){
        public = "private";
    }
    let category = event.category;
    let color = eventColor(category);
    
    if(eventId != null){
        if(document.getElementById(color+"_checkbox")){
            if(document.getElementById(color+"_checkbox").checked){
                day.innerHTML += '<br/><button id=' + eventId + ' value=' + title + ' style=background-color:' + color + '>' + title + '</button>';
            }
        }
        let viewId = "event" + eventId;
        let editId = "edit" + eventId;
        let deleteId = "delete" + eventId;
        day.innerHTML += '<div id=' + viewId + ' title=' + title + '><p>' + description + '</p><p>' + date + ' at ' + time + '</p><p>This is a ' + public + ' event</p><p>' + category + '</p><input type="button" id=' + editId + ' value="Edit"> | <input type="button" id=' + deleteId + ' value="Delete"></div>';
        if(document.getElementById(viewId)){
            document.getElementById(viewId).style.display = "none";
            if(document.getElementById(eventId)){
                document.getElementById(eventId).addEventListener("click", function(){
                    $("#event" + eventId).dialog()
                }, false);
            }
        }
    }
}

// display the edit button for events
function displayEditEvent(event) {
    let eventId = event.eventid;
    if(eventId != null){
        if(document.getElementById('edit' + eventId)){
            document.getElementById('edit' + eventId).addEventListener("click", function(){
                $("#event" + eventId).dialog('close');
                $('#edit_event_section').dialog();
                document.getElementById("edit_event_button").addEventListener("click", function(){
                    editEventAjax(event);
                    $("#edit_event_section").dialog('close');
                    displayCalendar(month)
                }, false);
            }, false);
        }
    }
}

// display the delete button for events
function displayDeleteEvent(event) {
    let eventId = event.eventid;
    if(eventId != null){
        if(document.getElementById('delete' + eventId)){
            document.getElementById('delete' + eventId).addEventListener("click", function(){
                $("#event" + eventId).dialog('close');
                const idToDelete = { "eventid" : eventId };
                fetch("eventDelete.php", {
                    method: 'POST',
                    body: JSON.stringify(idToDelete),
                    headers: { 'content-type': 'application/json' }
                })
                .then(response => response.text())
                .then(text => console.log(text))
                .catch(err => console.error(err));
                displayCalendar(month);
            }, false);
        }
    }
}

// displaying the calendar
function displayCalendar(month) {

    let calendar = document.getElementById("calendar_grid");
    while(calendar.childNodes.length >= 2) {
        calendar.removeChild(calendar.lastChild);
    }
  
    let weeks = month.getWeeks();
    for (let i in weeks) {
        let days = weeks[i].getDates();
        let week = document.createElement("tr");
        for (let j in days) {
            let day = document.createElement("td");
            if (days[j].getMonth() == month.month) {
                day.appendChild(document.createTextNode(days[j].getDate()));
                day.setAttribute("class", "day_in_month");
                const data = { "month" : month.month + 1, "day" : days[j].getDate()};

                // Getting events
                fetch("eventGet.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        let events = res.events;
                        let totalEvents = events.length;
                        for (let k = 0; k < totalEvents; k++) {
                            displayEvent(events[k], day);
                            displayEditEvent(events[k]);
                            displayDeleteEvent(events[k]);
                        }
                    }
                })
                .catch(err => console.error(err));
            } else {
                day.setAttribute("class", "day_out_of_month");
            }
            week.appendChild(day);
        }
        $("#calendar_grid").append(week);
    }
}

// Adding a new event
function addEventAjax() {
    const title = document.getElementById("event_title").value;
    const description = document.getElementById("event_desc").value;
    const date = document.getElementById("event_date").value; 
    const time = document.getElementById("event_time").value;
    const isPublic = document.getElementById("public").checked; 
    const category = document.getElementById("event_cat").value;
    const data = { 'title' : title, 
                'description' : description,
                'date' : date,
                'time' : time,
                'is_public' : isPublic,
                'category':category
            };

    fetch("eventAdd.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(res => {if (res.success){
                console.log("Successfully Added");
            } else {
                console.log("Failed to add new event");
            }
        })
        .catch(err => console.error(err));
    displayCalendar(month);
}

// editing an already existent event
function editEventAjax(event) {
    const title = document.getElementById("edit_event_title").value;
    const description = document.getElementById("edit_event_desc").value;
    const date = document.getElementById("edit_event_date").value; 
    const time = document.getElementById("edit_event_time").value;
    const isPublic = document.getElementById("edit_public").checked; 
    const category = document.getElementById("edit_event_cat").value;

    const data = {'eventid' : event.eventid, 
                'title' : title, 
                'description' : description,
                'date' : date,
                'time' : time,
                'is_public' : isPublic,
                'category' : category};

    fetch("eventEdit.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.text())
        .then(res => console.log(res))
        .catch(err => console.error(err));
}

// loading the appropriate screen when the app starts
document.addEventListener("DOMContentLoaded", function(){
    loginCheckAjax();
    display();
}, false);

// Event liteners for all buttons and checkboxes
document.getElementById("login_button").addEventListener("click", loginAjax, false);
document.getElementById("signup_button").addEventListener("click", signupAjax, false);
document.getElementById("logout_button").addEventListener("click", logoutAjax, false);
document.getElementById("add_event_menu_button").addEventListener("click", function(){
    $("#add_event_section").dialog();
}, false);
document.getElementById("add_event_button").addEventListener("click", function(){
    addEventAjax();
    $("#add_event_section").dialog('close');
    display();
}, false);
document.getElementById("next").addEventListener("click", function(){
    month = month.nextMonth();
    if (month.month == 0){
        year++;
    }
    display();
}, false);

document.getElementById("previous").addEventListener("click", function(){
    month = month.prevMonth();
    if (month.month == 11){
        year--;
    }
    display();
}, false);
document.getElementById("yellow_checkbox").addEventListener("click", display, false);
document.getElementById("white_checkbox").addEventListener("click", display, false);
document.getElementById("orange_checkbox").addEventListener("click", display, false);
document.getElementById("lightblue_checkbox").addEventListener("click", display, false);
document.getElementById("lightgreen_checkbox").addEventListener("click", display, false);
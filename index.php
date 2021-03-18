<?php
    //ini_set("session.cookie_httponly", 1);
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendar</title>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/start/jquery-ui.css" type="text/css" rel="Stylesheet" />
    <link href="style.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js"></script>
</head>
<body>
    <div class="welcome">
        <div class="authentication">
            <div id="login_section" class="connect">
                <h3>Existing User: Log In</h3>
                <input type="text" id="l_usr" name="l_usr" maxlength="20"  placeholder="Type Username" /><br><br>
                <input type="password" id="l_pwd" name="l_pwd" maxlength="20" placeholder="Type Password" /><br><br>
                <input type="button" id="login_button" value="Log In"/>
            </div>

            <div id="signup_section" class="connect">
                <h3>New User: Sign Up</h3>
                <input type="text" id="s_usr" name="s_usr" maxlength="20"  placeholder="Type Username" /><br><br>
                <input type="password" id="s_pwd1" name="s_pwd1" maxlength="20" placeholder="Type Password" /><br><br>
                <input type="password" id="s_pwd2" name="s_pwd2" maxlength="20" placeholder="Retype Password" /><br><br>
                <input type="button" id="signup_button" value="Sign_up"/>
            </div>
        </div>
    </div>

    <div class="app">
        <div class="main">
            <div id="menu">
                <h3 id="welcome_msg">Welcome</h3>
                <input type="button" id="add_event_menu_button" value="Add Event" />
                <h4 id="add_edit_msg">Click on any event to view, edit or delete it</h4>
                <input type="button" id="logout_button" value="Log Out" /><br><br>
                <h4>Categories</h4>
                <table id="colors">
                    <tr>
                        <td><input type="checkbox" id="yellow_checkbox" value="yellow" checked></td>
                        <td>Work</td>
                        <td id="yellow"></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="lightblue_checkbox" value="lightblue" checked></td>
                        <td>Home</td>
                        <td id="lightblue"></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="lightgreen_checkbox" value="lightgreen" checked></td>
                        <td>School</td>
                        <td id="lightgreen"></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="orange_checkbox" value="orange" checked></td>
                        <td>Sports</td>
                        <td id="orange"></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="white_checkbox" value="white" checked></td>
                        <td>None</td>
                        <td id="white"></td>
                    </tr>
                </table>
            </div>

            <div id="calendar">
                <div id="header">
                    <input type="button" id="previous" value="Previous"/>
                    <div id="this_month"></div>
                    <input type="button" id="next" value="Next"/>
                </div>
                <br>

                <table id="days_names">
                    <tr>
                        <th>Sunday</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                    </tr>
                </table>

                <table id="calendar_grid">
                </table>
            </div>
        </div>
    </div>

    <div id="add_event_section" title="Add Event">
        <label for="event_title">Title</label>
        <input type="text" id="event_title" placeholder="Title" required><br><br>
        <label for="event_desc">Description</label>
        <input type="text" id="event_desc" placeholder="Description"><br><br>
        <label for="event_date">Date</label>
        <input type="date" id="event_date" required><br><br>
        <label for="event_time">Time</label>
        <input type="time" id="event_time" value="12:00" required><br><br>
        <input type="checkbox" id="public" value="public">
        <label for="public">Public</label><br><br>
        <label for="event_cat">Category</label>
        <select id="event_cat">
            <option value="none">None</option>
            <option value="work">Work</option>
            <option value="school">School</option>
            <option value="home">Home</option>
            <option value="sports">Sports</option>
        </select><br><br>

        <input type="button" id="add_event_button" value="Add"/>
    </div>

    <div id="edit_event_section" title="Edit Event">
        <label for="edit_event_title">Title</label>
        <input type="text" id="edit_event_title" placeholder="Title" required><br><br>
        <label for="edit_event_desc">Description</label>
        <input type="text" id="edit_event_desc" placeholder="Description"><br><br>
        <label for="edit_event_date">Date</label>
        <input type="date" id="edit_event_date" required><br><br>
        <label for="edit_event_time">Time</label>
        <input type="time" id="edit_event_time" value="12:00" required><br><br>
        <input type="checkbox" id="edit_public" value="edit_public">
        <label for="edit_public">Public</label><br><br>
        <label for="edit_event_cat">Category</label>
        <select id="edit_event_cat">
            <option value="none">None</option>
            <option value="work">Work</option>
            <option value="school">School</option>
            <option value="home">Home</option>
            <option value="sports">Sports</option>
        </select><br><br>

        <input type="button" id="edit_event_button" value="Edit"/>
    </div>

    <script type="text/javascript" src="script.js"></script>
</body>

# PHP-User-Authentication-Script-with-Login-Attempt-Blocking-and-Inactivity-Logout
This is a PHP script that handles user authentication for a website. It starts a session and checks if the user has submitted the login form. It then checks if the user entered an email address or a username and executes the corresponding SQL query to retrieve the user's information from the database. If the user exists, the script verifies the password entered by the user against the hashed password in the database. If the password is correct, it starts a session for the user and redirects them to the home page of the website.

If the user enters an incorrect password, the script increments a login attempts counter in the user's session. If the number of attempts exceeds 5, the script blocks the user for 6 hours by setting a blocked_until timestamp in the user's session.

If the user is already logged in, the script checks for inactivity. If the user has been inactive for 3 hours, the script logs them out by unsetting the session variables and destroying the session.

If the user is currently blocked, the script redirects them to a temporary login blocked page.

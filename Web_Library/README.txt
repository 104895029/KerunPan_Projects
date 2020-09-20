COMP-3540 World Wide Web Project
Project name: Online Library System
Author: Kerun Pan
Student ID: 104895029

There is an important important thing for you to read carefully on step 6.

Operation guide:

Step 1: Enter the user login website:
http://pan16.myweb.cs.uwindsor.ca/60334/project/html/real_html/User_Login_Real.php

Step 2: Click the create account button, create an user account.
After filling in all the information, choose whether the user is a student or a teacher. 
The student's credit rating is low and the end time cannot exceed 15 days.
The teacher's credit rating is high. The borrowing time can be as long as one month.
Finally, click Create Account button, enter the user login website.

Step 3: Fill in the username and password information that you created on create user page, then click sign in button.
Next you will go to library home page.

Step 4: Choose the book you want to borrow, but if the book number in stock is 0, you cannot borrow any book.

Step 5: Return book, you can return multipule books at a same time. 

Step 6: If the project is running normally, the book return time should be the same as the current time of the computer (CURRENT_TIMESTAMP),
but for the convenience of debugging and a more intuitive reflection, 
so the borrowing time exceeds the specified time, 
I hardcode the time in the Return_Book_Real.php file to 2020 -9-15 23:37:48, 
so students who can only borrow books for 15 days will be fined all, 
and teachers who return the books will not be fined. 
If you want to modify the time of Return_Book_Real.php to debug the mechanism of fines, 
you only need to modify the 24th line of public_html/60334/project/html/real_html/Return_Book_Real.php,
 2020-9-15 23:37:48 to CURRENT_TIMESTAMP Both can be successful.
 
Thanks for your read carefully. If you have any more question, please contact me via uwindsor mail.




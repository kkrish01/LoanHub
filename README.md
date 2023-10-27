Project name :- LoanHub
1. Fetch all the user details.
Method :- GET
Link :- http://localhost:8000/api/users

2. Insert the new user with post method.
Method :- POST
Link :- http://localhost:8000/api/users
Json data :-
{
    "name" : "Ravi Kumar",
    "email" : "ravi.190.10@gmail.com",
    "password" : "1234567890"
}

3. Get the specific user loan details.
Method :- GET
Link :- http://localhost:8000/api/loans/1

4. Loan request for specific user.
Method :- POST
Link :- http://localhost:8000/api/loans/1/request
Json data :-
{
    "amount" : 10000,
    "term" : 3
}

5. Loan Request approve by secific admin id.
Method :- PUT
Link :- http://localhost:8000/api/admin/1/request
Json data:-
{
    "loanid" : 4
}

6. Loan repayment by specific user.
Method :- PUT.
Link :- http://localhost:8000/api/repayment/1
Json data :-
{
    "loanid" : 4,
    "payment" : 5000
}

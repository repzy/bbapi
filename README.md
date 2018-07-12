## bbapi
Requirements
1. Extra user details entity should be an extendable object with mandatory description string field.
2. GitHub user ID is unique identifier of extra user details record.
3. Must-have API features:
a. create extra user details record;
b. get extra user details record;
4. Sample data seeding script should select any 1000 user IDs from GitHub and populate extra user details records for them in the
storage using user ID as description field value.

## Sequence of commands

```bash
git clone https://github.com/repzy/bbapi
```
Don't forget to change DATABASE_URL in .env file 
```bash
bin/console doctrine:database:create
```
```bash
bin/console doctrine:schema:create
```
To get data from GitHub run
```bash
bin/console populate:github
```

Available routes

| Name            | Route                   |
| --------------- | ----------------------- |
| get_details 	  | /details.{_format}      |
| post_detail 	  | /details.{_format}      |
| get_detail 	    | /details/{id}.{_format} |
| delete_detail 	| /details/{id}.{_format} | 

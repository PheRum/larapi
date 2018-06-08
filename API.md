## Install
`composer update`

edit `.env` file, add api key for `MAIL_CHIMP_API_KEY`

## Route

**Lists**

`GET` `http://api.local/api/list` - Get information about all lists

`POST` `http://api.local/api/list` - Create a new list

`GET` `http://api.local/api/list/{list_id}` - Get information about a specific list

`PATCH` `http://api.local/api/list/{list_id}` - Update a specific list

`DELETE` `http://api.local/api/list/{list_id}` - Delete a list

**Members**

`GET` `http://api.local/api/list/{list_id}/member` - Get information about members in a list

`POST` `http://api.local/api/list/{list_id}/member` - Add a new list member

`GET` `http://api.local/api/list/{list_id}/member/{hash}` - Get information about a specific list member

`PATCH` `http://api.local/api/list/{list_id}/member/{hash}` - Update a list member

`DELETE` `http://api.local/api/list/{list_id}/member/{hash}` - Remove a list member
# BIG UPDATE V2.2

Big update for package with many feature, stay tune for complete documentation

1. **Return Controller Directly from Service**
   - Previously, our return was a `resource::class`, which caused errors not to be visible when using the API until we returned from the service. Now, errors will be visible immediately.

2. **More Complex Data Flexibility**
   - By switching to direct returns from the service, resources can still be used by simply changing the `resourceClass` in the `__construct`. If you don’t want to use it, you can set its value to `null`.

3. **File Upload V3**
   - Similar to V1, File Upload V3 gives you more freedom. You can directly input file objects into your functions, so there are no issues if the file is an array.

4. **Where Function**
   - Apologies for missing this function earlier. It is now available as a base for the where function. You can use `QueryOptions` for your query options, whether you want to `get()` or `first()`.

5. **Optimize Pagination**
   - Pagination data is now more complex, with customizable pagination types available directly from the request. You can also retrieve all data with pagination output.

6. **Fixing Repository Errors**
   - Correcting errors in repository class writing.

7. **Fixing Single Data Output**
   - Fixing resource output for single data such as `find` and `findOrFail`.

8. **Fixing Pagination Output**
   - Fixing duplicate pagination output.

9. **Pagination Collection**
   - You can now paginate any data, not just model data.

10. **Update Environment**
    - A repository function is now available for updating the environment (`.env`).

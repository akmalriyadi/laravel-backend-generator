# BIG UPDATE V2.2

Big update for package with many feature, stay tune for complete documentation

1. **Return Controller Directly from Service**
   
   Previously, our return was a `resource::class`, which caused errors not to be visible when using the API until we returned from the service. Now, errors will be visible immediately.

2. **More Complex Data Flexibility**

    By switching to direct returns from the service, resources can still be used by simply changing the `resourceClass` in the `__construct`. If you don’t want to use it, you can set its value to `null`.

3. **File Upload V3**
   
   Similar to V1, File Upload V3 gives you more freedom. You can directly input file objects into your functions, so there are no issues if the file is an array.

4. **Where Function**
   
   Apologies for missing this function earlier. It is now available as a base for the where function. You can use `QueryOptions` for your query options, whether you want to `get()` or `first()`.

5. **Optimize Pagination**
   
   Pagination data is now more complex, with customizable pagination types available directly from the request. You can also retrieve all data with pagination output.

6. **Fixing Repository Errors**
   
   Correcting errors in repository class writing.

7. **Fixing Single Data Output**
   
   Fixing resource output for single data such as `find` and `findOrFail`.

8. **Fixing Pagination Output**
   
   Fixing duplicate pagination output.

9. **Pagination Collection**
   
   You can now paginate any data, not just model data.

10. **Update Environment**
    
    A repository function is now available for updating the environment (`.env`).

# Support My Work

Thank you for visiting my GitHub repository! Your interest in my work means a lot to me. If you find this project helpful or valuable, please consider supporting its development. 

Creating and maintaining this project requires significant time and effort. Your support will enable me to continue improving and adding new features to this project.

## How You Can Help

1. **Star the Repository**: Show your appreciation by giving this repository a star. It helps increase visibility and encourages more contributors to join.

2. **Share with Others**: If you know someone who might benefit from this project, please share it with them.

3. **Make a Donation**: If you're in a position to contribute financially, any amount would be greatly appreciated. Your donations will directly support the ongoing development and maintenance of this project.

   **Donate via PayPal**: [zainnoeryadie@gmail.com](https://www.paypal.com/paypalme/zainnoeryadie)

4. **Have a Special Request or Need Assistance?**: If you have any specific requests, ideas for improvement, or if you require assistance related to this project, feel free to reach out. You can email me directly at [zainnoeryadie@gmail.com](mailto:zainnoeryadie@gmail.com).

Thank you for your support!

Planned features and improvements:

1.  Write test, many tests;
2.  Handle failed payments with kkb_epay_callback_post_failure();
3.  Add support for other currencies than tenge;
4.  Move 90% of code into namespaced classes (have to drop PHP5.2 support to
    do that) and minimize Drupal dependence;
5.  Add Russian translation;
6.  Add Kazakh translation;
7.  Query 'payments monitor' to check orders statuses;
8.  Create interface for confirming and canceling payments;
9.  Save all raw server responses into logs or database;
10. Log every 'epay/post/success' page request;
11. Send user's prefered laguage (rus|eng) to the payment page;
12. Write API usage examples;
13. Create kkb_epay_ubercart module;
14. Create kkb_epay_commerce module;
15. Add module description.

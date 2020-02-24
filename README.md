# Overview

The WSU Central Payment Site is a toolkit that allows WSU departments to provide a secure, PCI compliant, 
 payment method to their applications.<br>
The Central Payment Site toolkit (CPS) consists of web services, interacting with a secure database, and a web page,
 which prompts the customer for the payment (credit card) details. This web page is displayed from a secure central 
 server, hosted by [Cybersource](https://www.cybersource.com).

So, when it is time to collect a payment from a customer, your application re-directs to the CPS secure web page. 
 After collecting the payment from the customer, the system redirects back to your application and you can 
 process the result of the payment (e.g. displaying a receipt to the customer, crediting the customer’s account, 
 initiating shipment processes, etc.).
 
Because your application does not "input, process or store" the credit card information, it is by definition not 
 subject to the [PCI Data Security standards](https://en.wikipedia.org/wiki/Payment_Card_Industry_Data_Security_Standard).
 
This library wants to provide the user a simple and reliable interface to the WSU CPS system, together with
 up to date documentation regarding the process flow.


## Getting Started

The web service interface and definitions can be found at
[https://ewebservice.wsu.edu/CentralPaymentSite_WS/service.asmx](https://ewebservice.wsu.edu/CentralPaymentSite_WS/service.asmx)

A sandbox/test environment is available at
 [https://test-ewebservice.wsu.edu/CentralPaymentSite_WS/service.asmx](https://test-ewebservice.wsu.edu/CentralPaymentSite_WS/service.asmx)


#### Merchant ID

Before you can begin processing credit cards you will need a merchant number (`merchant_id`), which identifies your account with the WSU 
merchant bank and determines the description of the charge that will occur on your bank statement.

WSU requests merchant numbers as needed for new applications.

#### Transaction type

When a credit card transaction from your application is approved, your department's budget-project in the WSU 
 accounting system gets credit for the transaction. However, your application does not reference the budget-project 
 directly. Instead, it references a code (`trans_type`) linked to a specific budget-project, source and sub-source.
 An application may use more than one transaction type if you want revenue to be credited to more than one account; for example
 a conferencing application might want the revenue for each conference to be credited to a separate account.

Controller’s office staff is responsible for setting up new transaction types.

#### API Security

All WSU CPS services are secured by IP address, therefore your application needs to use a static IP.

This means the IP of all your app instances/machines (development, production and possibly testing) must be added to the list 
 of permitted IP addresses.
 Contact WSU Central IT to whitelist a new IP.


### Process flow

| Your App | WSU CPS |
| --- | --- |
| 1. Call `AuthCapRequestWithCancelURL` web service to<br> preauthorize the payment, generating a `GUID` code<br> identifying the transaction | |
| 2. Redirect to the CPS (Cybersource) payment form<br> page, where the user submits the payment request,<br> or cancels the operation | 2. Collects the credit card info and processes the payment |
|   | 3a. Redirects to `ReturnURL` or`CancelURL` supplied by your app<br> 3b. POSTs to the `PostbackURL` supplied by your app<br> The transaction is marked as "processed" |
| 4. Call `AuthCapResponse` to complete the transaction, marking it "completed" | |

> **Note:** At any time your app can read the status and the data associated with the transaction calling `ReadPaymentAuthorization`

![flow](/docs/flow.png "Flow")


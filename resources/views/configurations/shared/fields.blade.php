<x-field.input name="ticket_price" label="Ticket Price" width="md"
                :readonly='false'
                value="{{ old('ticket_price', $configuration->ticket_price) }}"/>

<x-field.input name="registered_customer_ticket_discount" label="Customer Ticket Discount Price" width="md"
                :readonly='false'
                value="{{ old('registered_customer_ticket_discount', $configuration->registered_customer_ticket_discount) }}"/>

{include file='Base.part.header.tpl'}

<h1>{$title|default:'Reservation - choose rooms':tg}</h1>

Together with preview action we can have more virtual models, 
first only with rooms, second with info without registration, third for logged user, ...

{include file='Base.part.cleanForm.tpl'  form=$reservationForm formId=reservationForm}

{include file='Base.part.footer.tpl'}


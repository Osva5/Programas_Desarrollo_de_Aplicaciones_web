<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo simple 10</title>
</head>
<body>
    <H1> Datos introducidos por el usuario:</H1><BR>
    <% for each V_Entrada in Request.Form
        for Indice=1 to Request.Form(V_Entrada).Count %>
    <%=V_Entrada%> =
    <%=Request.Form(V-Entrada)(Indice)%><BR>
    <%next
    next %>
    <BR><H2> Todo junto: </H2><BR>
    <%=Request.Form%><BR><BR>
</body>
</html>
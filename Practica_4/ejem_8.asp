<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo simple 8</title>
</head>
<body>
    <H1> Datos introducidos por el usuario: </H1> <BR> Nombre:
    <%=Request.Form("V_Nombre")%><BR> Apellido: 
    <%=Request.Form("V_Apellido")%><BR> Matricula: 
    <%=Request.Form("V_Matricula")%><BR> e-mail:
    <%=Request.Form("V_Correo")%><BR> 

    Seminario 1: <%=Split(Request.Form("V_Seminario"), ",")(0)%><BR>
    Seminario 2: <%=Split(Request.Form("V_Seminario"), ",")(1)%><BR>
    Seminario 3: <%=Split(Request.Form("V_Seminario"), ",")(2)%><BR>
    Seminario 4: <%=Split(Request.Form("V_Seminario"), ",")(3)%><BR>
    <BR><H2> Todo junto: </H2><BR>
    <%=Request.Form%><BR><BR>
        
</body>
</html>
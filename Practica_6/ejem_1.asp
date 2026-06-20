<%@ Language="VBScript" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo Sencillo de BD</title>
</head>
<body>
    <h3> Tabla "Fichas" de la base de datos "Ejemplo_1" </h3>

    <% 
    ' 1. Definición de objetos
    Dim Ob_Conn, Ob_RS
    Set Ob_Conn = Server.CreateObject("ADODB.Connection")
    
    ' 2. Abrir conexión usando el DSN llamado "Alumnos"
    ' Nota: asegúrate que en el Administrador ODBC, el DSN se llame "Alumnos"
    Ob_Conn.Open "DSN=Alumnos"
    
    ' 3. Ejecutar consulta
    Set Ob_RS = Ob_Conn.Execute("SELECT * FROM Datos_Alumnos")
    %>

    <CENTER>
        <TABLE BORDER="1">
            <TR>
                <TH> DNI </TH>
                <TH> NOMBRE </TH>
                <TH> APELLIDOS </TH>
                <TH> DIRECCION </TH>
                <TH> TELEFONO </TH>
            </TR>
            <% 
            ' 4. Bucle para recorrer la tabla
            Do While Not Ob_RS.EOF 
            %>
                <TR>
                    <TD><%=Ob_RS("DNI")%></TD>
                    <TD><%=Ob_RS("Nombre")%></TD>
                    <TD><%=Ob_RS("Apellidos")%></TD>
                    <TD><%=Ob_RS("Direccion")%></TD>
                    <TD><%=Ob_RS("Telefono")%></TD>
                </TR>
            <% 
                Ob_RS.MoveNext
            Loop 
            
            ' 5. Limpieza
            Ob_RS.Close
            Ob_Conn.Close
            Set Ob_RS = Nothing
            Set Ob_Conn = Nothing
            %>
        </TABLE>
    </CENTER>
</body>
</html>
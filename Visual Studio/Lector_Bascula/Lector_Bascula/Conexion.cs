using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Management;

namespace Lector_Bascula
{
    public class Conexion
    {
        Conexion()
        {
        }

        public static ConnectionOptions ProcessConnectionOptions()
        {
            return new ConnectionOptions
            {
                Impersonation = ImpersonationLevel.Impersonate,
                Authentication = AuthenticationLevel.Default,
                EnablePrivileges = true,
            };
        }

        public static ManagementScope ConnectionScope(String machineName, ConnectionOptions options, String path)
        {
            ManagementScope managementScope = new ManagementScope();

            managementScope.Path = new ManagementPath("\\\\" + machineName + path);
            managementScope.Options = options;
            managementScope.Connect();

            return managementScope;
        }
    }
}

using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Management;

namespace Lector_Bascula
{
    public class PortInfo
    {
        String name;
        String description;

        PortInfo()
        {
        }

        public String Name
        {
            get
            {
                return this.name;
            }

            set
            {
                this.name = value;
            }
        }

        public String Description
        {
            get
            {
                return this.description;
            }

            set
            {
                this.description = value;
            }
        }

        public static List<PortInfo> GetPortsInfo()
        {
            List<PortInfo> portsInfo = new List<PortInfo>();
            ConnectionOptions options = Conexion.ProcessConnectionOptions();
            ManagementScope scope = Conexion.ConnectionScope(Environment.MachineName, options, "\\root\\CIMV2");
            ObjectQuery query = new ObjectQuery("SELECT * FROM Win32_PnPEntity WHERE ConfigManagerErrorCode = 0");
            ManagementObjectSearcher managementObjectSearcher = new ManagementObjectSearcher(scope, query);

            using (managementObjectSearcher)
            {
                using (ManagementObjectCollection.ManagementObjectEnumerator enumerator = managementObjectSearcher.Get().GetEnumerator())
                {
                    while (enumerator.MoveNext())
                    {
                        ManagementObject managementObject = (ManagementObject)enumerator.Current;
                        if (managementObject != null)
                        {
                            object obj = managementObject["Caption"];
                            if (obj != null)
                            {
                                String str = obj.ToString();
                                if (str.Contains("(COM"))
                                {
                                    
                                    portsInfo.Add(new PortInfo{
                                        Name = str.Substring(str.LastIndexOf("(COM")).Replace("(", String.Empty).Replace(")", String.Empty),
                                        Description = str
                                    });   
                                }
                            }
                        }
                    }
                }
            }
            return portsInfo;
        }
       
    }
}

using System;
using System.IO;
using System.Diagnostics;
using System.Windows.Forms;
using System.Management;

namespace Connect_Form
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        //Connect Button: When clicked, execute the following function
        private void button1_Click(object sender, EventArgs e)
        {
            string port;

            //Get the port ID and store it onto a text file, then run the specified .cmd files
            port = getPort();
            recordPort(port);
            executeCommands();

            Close();
        }

        //Cancel Button: When clicked, close the program
        private void button2_Click(object sender, EventArgs e)
        {
            Close();
        }

        //Opens the specified .cmd files and executes them on a different independent process
        static void executeCommands()
        {
            //Decalre new process object
            ProcessStartInfo psi = new ProcessStartInfo("cmd");

            //Initialize process attributes 
            psi.UseShellExecute = false;
            psi.RedirectStandardInput = true;
            psi.RedirectStandardOutput = true;
            psi.CreateNoWindow = true;

            //Start the first process
            Process p = new Process();
            p.StartInfo = psi;
            p.Start();
            p.StandardInput.WriteLine("runNode");

            //Start the second process
            Process p2 = new Process();
            p2.StartInfo = psi;
            p2.Start();
            p2.StandardInput.WriteLine("openDisconnect");

            return;
        }
        
        //This function fetches the port ID for the ArduinoMKR1000
        static string getPort()
        {
            ManagementScope connectionScope = new ManagementScope();
            SelectQuery serialQuery = new SelectQuery("SELECT * FROM Win32_SerialPort");
            ManagementObjectSearcher searcher = new ManagementObjectSearcher(connectionScope, serialQuery);

            try
            {
                foreach (ManagementObject item in searcher.Get())
                {
                    string desc = item["Description"].ToString();
                    string deviceId = item["DeviceID"].ToString();

                    if (desc.Contains("Arduino"))
                    {
                        return deviceId;
                    }
                }
            }
            catch (ManagementException e)
            {
                Console.WriteLine("Error: " + e.Message);
            }

            return null;
        }
        
        //Save the found port ID onto a text file called "PortID.txt"
        static void recordPort(string port)
        {
            try
            {
                //Pass the filepath and filename to the StreamWriter Constructor
                StreamWriter sw = new StreamWriter(".\\PortID.txt");

                //Write a line of text
                sw.Write(port);

                //Close the file
                sw.Close();
            }
            catch (Exception e)
            {
                Console.WriteLine("Exception: " + e.Message);
            }
            finally
            {
                Console.WriteLine("Executing finally block.");
            }
        }
    }
}

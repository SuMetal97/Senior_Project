using System;
using System.Diagnostics;
using System.Windows.Forms;

namespace Disconnect_Form
{
    public partial class Form1 : Form
    {
        private const int CP_NOCLOSE_BUTTON = 0x200;
        public Form1()
        {
            InitializeComponent();
        }

        //This function is used to disable the quit button ("X" on top right corner)
        //it ensures that the program will exit normally and other independent 
        //processes are closed properly as well
        protected override CreateParams CreateParams
        {
            get
            {
                CreateParams myCp = base.CreateParams;
                myCp.ClassStyle = myCp.ClassStyle | CP_NOCLOSE_BUTTON;
                return myCp;
            }
        }

        //Disconnect Button: When clicked, prepare and start independent processes to clean 
        //other running processes and free main memory
        private void button1_Click(object sender, EventArgs e)
        {
            //Declare process creator object
            ProcessStartInfo psi = new ProcessStartInfo("cmd");

            //Initialize the descriptors of the process creator object
            psi.UseShellExecute = false;
            psi.RedirectStandardInput = true;
            psi.RedirectStandardOutput = true;
            psi.CreateNoWindow = true;

            //Begin the first process
            Process p = new Process();
            p.StartInfo = psi;
            p.Start();
            p.StandardInput.WriteLine("stopNode");

            //Begin the second process
            Process p2 = new Process();
            p2.StartInfo = psi;
            p2.Start();
            p2.StandardInput.WriteLine("openConnect");

            Close();
        }
    }
}

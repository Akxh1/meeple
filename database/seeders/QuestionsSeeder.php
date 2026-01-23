<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Question;
use App\Models\Answer;

class QuestionsSeeder extends Seeder
{
    /**
     * Seed 20 questions per module with answers.
     */
    public function run(): void
    {
        $questionsData = [
            1 => [ // Cyber Security Essentials
                ['What is the primary purpose of a firewall?', ['To filter network traffic', 'To store passwords', 'To cool the server', 'To backup data'], 0],
                ['Which attack involves overwhelming a server with traffic?', ['DDoS', 'Phishing', 'Keylogging', 'Rootkit'], 0],
                ['What does HTTPS stand for?', ['HyperText Transfer Protocol Secure', 'High Transfer Protocol System', 'Hyper Transfer Protected System', 'Host Transfer Protocol Secure'], 0],
                ['What is phishing?', ['A social engineering attack via email', 'A type of malware', 'A firewall configuration', 'A password manager'], 0],
                ['Which of these is a strong password?', ['Tr0ub4dor&3', 'password123', 'admin', '123456'], 0],
                ['What is two-factor authentication?', ['Using two verification methods', 'Using two passwords', 'Using two browsers', 'Using two devices'], 0],
                ['What is malware?', ['Malicious software', 'Mail software', 'Marketing software', 'Management software'], 0],
                ['What does VPN stand for?', ['Virtual Private Network', 'Very Private Network', 'Virtual Public Network', 'Verified Private Network'], 0],
                ['What is encryption?', ['Converting data to unreadable format', 'Deleting files', 'Copying files', 'Compressing files'], 0],
                ['What is a security patch?', ['Software update fixing vulnerabilities', 'A physical repair', 'A firewall rule', 'A backup file'], 0],
                ['What is ransomware?', ['Malware that encrypts files for payment', 'Software for ransom notes', 'A backup tool', 'An antivirus'], 0],
                ['What is social engineering?', ['Manipulating people to reveal information', 'Building social networks', 'Engineering social platforms', 'Creating social media'], 0],
                ['What is a zero-day vulnerability?', ['An unknown security flaw', 'A day without attacks', 'A fixed vulnerability', 'A scheduled update'], 0],
                ['What is the CIA triad in security?', ['Confidentiality, Integrity, Availability', 'Computer, Internet, Access', 'Central, Internal, Active', 'Control, Information, Analysis'], 0],
                ['What is penetration testing?', ['Authorized simulated attack', 'Installing software', 'Network monitoring', 'Data backup'], 0],
                ['What is a brute force attack?', ['Trying all password combinations', 'Physical server attack', 'Social engineering', 'Phishing attempt'], 0],
                ['What is SQL injection?', ['Inserting malicious SQL code', 'Database backup', 'Query optimization', 'Data encryption'], 0],
                ['What is a digital certificate?', ['Electronic credential verifying identity', 'Physical ID card', 'Password file', 'Backup document'], 0],
                ['What is endpoint security?', ['Protecting devices on a network', 'Securing only servers', 'Network cable protection', 'Physical security'], 0],
                ['What is a honeypot?', ['Decoy system to detect attackers', 'Sweet data storage', 'Backup server', 'Password manager'], 0],
            ],
            2 => [ // Cloud Computing Fundamentals
                ['What is cloud computing?', ['Delivering computing services over the internet', 'Computing in the sky', 'Local server management', 'Physical data centers only'], 0],
                ['What is IaaS?', ['Infrastructure as a Service', 'Internet as a Service', 'Integration as a Service', 'Information as a Service'], 0],
                ['What is PaaS?', ['Platform as a Service', 'Program as a Service', 'Protocol as a Service', 'Process as a Service'], 0],
                ['What is SaaS?', ['Software as a Service', 'Security as a Service', 'Storage as a Service', 'System as a Service'], 0],
                ['Which is a public cloud provider?', ['AWS', 'Local server', 'Private datacenter', 'Personal computer'], 0],
                ['What is a private cloud?', ['Cloud for single organization', 'Free cloud storage', 'Public cloud service', 'Shared cloud platform'], 0],
                ['What is hybrid cloud?', ['Mix of private and public cloud', 'Two public clouds', 'Two private clouds', 'Local servers only'], 0],
                ['What is cloud elasticity?', ['Automatic resource scaling', 'Cloud flexibility', 'Data compression', 'Network speed'], 0],
                ['What is multi-tenancy?', ['Multiple users sharing resources', 'Multiple servers', 'Multiple networks', 'Multiple passwords'], 0],
                ['What is a virtual machine?', ['Software emulation of a computer', 'A powerful physical server', 'A network device', 'A storage unit'], 0],
                ['What is containerization?', ['Packaging apps with dependencies', 'Shipping containers', 'Physical storage', 'Network isolation'], 0],
                ['What is serverless computing?', ['Cloud manages server infrastructure', 'No servers exist', 'Local servers only', 'Physical servers required'], 0],
                ['What is cloud migration?', ['Moving data to the cloud', 'Cloud backup', 'Deleting cloud data', 'Cloud security'], 0],
                ['What is a content delivery network?', ['Distributed servers for faster content', 'Single server network', 'Content creation tool', 'Data backup service'], 0],
                ['What is cloud storage?', ['Storing data on remote servers', 'Local hard drive storage', 'USB storage', 'CD-ROM storage'], 0],
                ['What is auto-scaling?', ['Automatically adjusting resources', 'Manual server management', 'Fixed resource allocation', 'Server shutdown'], 0],
                ['What is a cloud region?', ['Geographic area with data centers', 'Cloud price zone', 'Service type', 'User location'], 0],
                ['What is cloud computing latency?', ['Delay in data transfer', 'Cloud speed', 'Storage capacity', 'Network bandwidth'], 0],
                ['What is a load balancer?', ['Distributes traffic across servers', 'Weighs servers', 'Measures data', 'Balances budgets'], 0],
                ['What is object storage?', ['Storing data as objects', 'Physical object storage', 'File system storage', 'Block storage'], 0],
            ],
            3 => [ // Data Science and Analytics
                ['What is data science?', ['Extracting insights from data', 'Science of data centers', 'Database management', 'Data entry'], 0],
                ['What is machine learning?', ['Systems learning from data', 'Machines learning to build', 'Manual programming', 'Data storage'], 0],
                ['What is a dataset?', ['Collection of data', 'Data center', 'Database software', 'Data analysis'], 0],
                ['What is data visualization?', ['Graphical representation of data', 'Data storage', 'Data deletion', 'Data collection'], 0],
                ['What is regression analysis?', ['Predicting continuous values', 'Classifying categories', 'Clustering data', 'Sorting data'], 0],
                ['What is classification?', ['Categorizing data into groups', 'Predicting numbers', 'Sorting files', 'Cleaning data'], 0],
                ['What is clustering?', ['Grouping similar data points', 'Classifying data', 'Predicting values', 'Sorting alphabetically'], 0],
                ['What is overfitting?', ['Model too complex for data', 'Model too simple', 'Perfect fit', 'Data error'], 0],
                ['What is a neural network?', ['Computing system inspired by brain', 'Network of neurons', 'Social network', 'Computer network'], 0],
                ['What is feature engineering?', ['Creating input variables', 'Engineering software', 'Building features', 'Designing systems'], 0],
                ['What is cross-validation?', ['Testing model on different data', 'Checking data twice', 'Validating users', 'Network testing'], 0],
                ['What is a confusion matrix?', ['Table showing prediction results', 'Confusing data display', 'Error chart', 'Random matrix'], 0],
                ['What is precision in ML?', ['True positives / predicted positives', 'Total accuracy', 'Speed of model', 'Data quality'], 0],
                ['What is recall in ML?', ['True positives / actual positives', 'Memory usage', 'Speed of recall', 'Data retrieval'], 0],
                ['What is a decision tree?', ['Tree-like model for decisions', 'Physical tree diagram', 'Programming structure', 'Data storage'], 0],
                ['What is random forest?', ['Ensemble of decision trees', 'Forest data', 'Random data', 'Tree plantation algorithm'], 0],
                ['What is data cleaning?', ['Removing errors from data', 'Washing data drives', 'Deleting all data', 'Formatting drives'], 0],
                ['What is exploratory data analysis?', ['Initial data investigation', 'Final analysis', 'Data storage', 'Data deletion'], 0],
                ['What is big data?', ['Extremely large datasets', 'Important data', 'Sized data', 'Backup data'], 0],
                ['What is data mining?', ['Discovering patterns in data', 'Mining for data drives', 'Data extraction', 'Physical mining'], 0],
            ],
            4 => [ // Network Infrastructure
                ['What is a router?', ['Device that forwards data packets', 'A computer', 'A cable', 'A storage device'], 0],
                ['What is an IP address?', ['Unique identifier for devices', 'Internet password', 'Email address', 'Website name'], 0],
                ['What is DNS?', ['Domain Name System', 'Data Network Service', 'Digital Name Server', 'Domain Number System'], 0],
                ['What is TCP/IP?', ['Network communication protocols', 'File transfer method', 'Email protocol', 'Storage system'], 0],
                ['What is a switch?', ['Device connecting network devices', 'Power button', 'Light switch', 'Router type'], 0],
                ['What is bandwidth?', ['Maximum data transfer rate', 'Network cable', 'Bandwidth of music', 'Internet speed only'], 0],
                ['What is a subnet?', ['Division of an IP network', 'Submarine network', 'Small internet', 'Network device'], 0],
                ['What is DHCP?', ['Protocol assigning IP addresses', 'Data hosting protocol', 'Dynamic hosting control', 'Device hosting control'], 0],
                ['What is a MAC address?', ['Hardware identifier', 'Apple computer address', 'Email address', 'Website URL'], 0],
                ['What is a gateway?', ['Network access point', 'Physical gate', 'Door to server room', 'Password entry'], 0],
                ['What is ethernet?', ['Wired networking technology', 'Wireless technology', 'Internet protocol', 'Cloud service'], 0],
                ['What is a VLAN?', ['Virtual Local Area Network', 'Virtual Long Area Network', 'Very Local Area Network', 'Visual LAN'], 0],
                ['What is network topology?', ['Network arrangement structure', 'Network speed', 'Network security', 'Network size'], 0],
                ['What is a modem?', ['Modulator-demodulator device', 'Modern network', 'Mobile device', 'Monitor device'], 0],
                ['What is packet switching?', ['Data transmission method', 'Package delivery', 'Switching packets physically', 'Network device'], 0],
                ['What is latency?', ['Delay in data transmission', 'Data speed', 'Network error', 'Bandwidth'], 0],
                ['What is a proxy server?', ['Intermediary between client and server', 'Backup server', 'Main server', 'Storage server'], 0],
                ['What is NAT?', ['Network Address Translation', 'Network Access Tool', 'Network Analysis Technique', 'Network Authentication Token'], 0],
                ['What is a firewall rule?', ['Network traffic policy', 'Fire safety rule', 'Server configuration', 'Password policy'], 0],
                ['What is QoS?', ['Quality of Service', 'Quantity of Service', 'Query of Service', 'Queue of Service'], 0],
            ],
            5 => [ // IT Project Management
                ['What is Agile methodology?', ['Iterative development approach', 'Slow development', 'Single phase development', 'Traditional planning'], 0],
                ['What is a sprint in Scrum?', ['Time-boxed development period', 'Running fast', 'Final release', 'Planning phase'], 0],
                ['What is a project stakeholder?', ['Person with interest in project', 'Project manager only', 'Developer', 'Customer only'], 0],
                ['What is the Waterfall model?', ['Sequential development phases', 'Agile method', 'Iterative approach', 'Random development'], 0],
                ['What is a Gantt chart?', ['Project schedule visualization', 'Budget chart', 'Team structure', 'Risk matrix'], 0],
                ['What is scope creep?', ['Uncontrolled project expansion', 'Project reduction', 'Budget increase', 'Team growth'], 0],
                ['What is a project milestone?', ['Significant project checkpoint', 'Daily task', 'Small bug fix', 'Team meeting'], 0],
                ['What is risk management?', ['Identifying and mitigating risks', 'Avoiding all risks', 'Taking risks', 'Ignoring risks'], 0],
                ['What is a product backlog?', ['List of features to develop', 'Bug list', 'Completed features', 'Deleted features'], 0],
                ['What is a retrospective?', ['Team review meeting', 'Looking backward', 'Historical data', 'Past projects'], 0],
                ['What is a user story?', ['Feature from user perspective', 'User biography', 'Story about users', 'Marketing content'], 0],
                ['What is MVP?', ['Minimum Viable Product', 'Maximum Valid Product', 'Most Valuable Product', 'Minimum Valid Product'], 0],
                ['What is a burndown chart?', ['Chart showing remaining work', 'Chart of burned resources', 'Fire safety chart', 'Budget chart'], 0],
                ['What is Kanban?', ['Visual workflow management', 'Japanese food', 'Programming language', 'Project name'], 0],
                ['What is a project charter?', ['Document authorizing project', 'Team list', 'Budget document', 'Technical specification'], 0],
                ['What is change management?', ['Handling project changes', 'Changing management team', 'New company', 'Budget changes'], 0],
                ['What is resource allocation?', ['Assigning resources to tasks', 'Buying resources', 'Resource deletion', 'Team building'], 0],
                ['What is a work breakdown structure?', ['Hierarchical task decomposition', 'Broken project', 'Failed structure', 'Team hierarchy'], 0],
                ['What is continuous integration?', ['Frequent code merging', 'Continuous meetings', 'Non-stop coding', 'Integration testing only'], 0],
                ['What is technical debt?', ['Shortcuts causing future work', 'Financial debt', 'Technical skills', 'Learning curve'], 0],
            ],
            6 => [ // Software Engineering Principles
                ['What is SDLC?', ['Software Development Life Cycle', 'System Design Life Cycle', 'Software Deployment Life Cycle', 'System Development Loop Cycle'], 0],
                ['What is version control?', ['Tracking code changes', 'Software versions for sale', 'Control panel', 'Version naming'], 0],
                ['What is Git?', ['Distributed version control system', 'Programming language', 'Database', 'Cloud service'], 0],
                ['What is refactoring?', ['Improving code without changing behavior', 'Rewriting from scratch', 'Adding features', 'Fixing bugs'], 0],
                ['What is unit testing?', ['Testing individual components', 'Testing entire system', 'User testing', 'Performance testing'], 0],
                ['What is code review?', ['Examining code for quality', 'Writing reviews about code', 'Deleting code', 'Running code'], 0],
                ['What is object-oriented programming?', ['Programming using objects and classes', 'Oriented objects', 'Physical programming', 'Functional programming'], 0],
                ['What is an API?', ['Application Programming Interface', 'Advanced Programming Interface', 'Automated Program Interface', 'Application Program Integration'], 0],
                ['What is debugging?', ['Finding and fixing errors', 'Removing bugs from office', 'Creating bugs', 'Testing bugs'], 0],
                ['What is a design pattern?', ['Reusable solution to common problems', 'Visual design', 'User interface design', 'Database design'], 0],
                ['What is DRY principle?', ['Dont Repeat Yourself', 'Dry code', 'Delete Repeated Yourself', 'Design Repeat Yearly'], 0],
                ['What is SOLID principles?', ['OOP design principles', 'Solid coding', 'Strong typing', 'Secure programming'], 0],
                ['What is inheritance?', ['Class inheriting from another', 'Money inheritance', 'Data transfer', 'File copying'], 0],
                ['What is polymorphism?', ['Objects taking many forms', 'Many shapes', 'Multiple users', 'Various colors'], 0],
                ['What is encapsulation?', ['Bundling data and methods', 'Capsule design', 'Data hiding only', 'Class creation'], 0],
                ['What is abstraction?', ['Hiding implementation details', 'Abstract art', 'Summary writing', 'Data mining'], 0],
                ['What is MVC architecture?', ['Model-View-Controller pattern', 'Main Virtual Computer', 'Multiple View Containers', 'Model Version Control'], 0],
                ['What is REST API?', ['Representational State Transfer API', 'Rest period API', 'Restore API', 'Reset API'], 0],
                ['What is microservices?', ['Small independent services', 'Microsoft services', 'Micro computers', 'Small databases'], 0],
                ['What is CI/CD?', ['Continuous Integration/Continuous Delivery', 'Computer Integration/Computer Delivery', 'Code Integration/Code Delivery', 'Central Integration/Central Delivery'], 0],
            ],
        ];

        foreach ($questionsData as $moduleId => $questions) {
            $module = Module::find($moduleId);
            if (!$module) continue;

            $this->command->info("Seeding questions for: {$module->name}");

            foreach ($questions as [$questionText, $answers, $correctIndex]) {
                $question = Question::create([
                    'question_text' => $questionText,
                    'type' => 'mcq',
                    'module_id' => $moduleId,
                ]);

                foreach ($answers as $index => $answerText) {
                    Answer::create([
                        'question_id' => $question->id,
                        'answer_text' => $answerText,
                        'is_correct' => $index === $correctIndex,
                    ]);
                }
            }
        }

        $this->command->info("✅ Seeded 20 questions per module for all 6 modules!");
    }
}

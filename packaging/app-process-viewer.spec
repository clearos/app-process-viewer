
Name: app-process-viewer
Epoch: 1
Version: 2.1.9
Release: 1%{dist}
Summary: Process Viewer
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base

%description
The Process Viewer provides a tabular display of all processes running on the system.  Specific information on each process is listed, including process ID, running time, CPU and memory usage.

%package core
Summary: Process Viewer - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: procps

%description core
The Process Viewer provides a tabular display of all processes running on the system.  Specific information on each process is listed, including process ID, running time, CPU and memory usage.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/process_viewer
cp -r * %{buildroot}/usr/clearos/apps/process_viewer/


%post
logger -p local6.notice -t installer 'app-process-viewer - installing'

%post core
logger -p local6.notice -t installer 'app-process-viewer-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/process_viewer/deploy/install ] && /usr/clearos/apps/process_viewer/deploy/install
fi

[ -x /usr/clearos/apps/process_viewer/deploy/upgrade ] && /usr/clearos/apps/process_viewer/deploy/upgrade



exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-process-viewer - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-process-viewer-core - uninstalling'
    [ -x /usr/clearos/apps/process_viewer/deploy/uninstall ] && /usr/clearos/apps/process_viewer/deploy/uninstall
fi



exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/process_viewer/controllers
/usr/clearos/apps/process_viewer/htdocs
/usr/clearos/apps/process_viewer/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/process_viewer/packaging
%dir /usr/clearos/apps/process_viewer
/usr/clearos/apps/process_viewer/deploy
/usr/clearos/apps/process_viewer/language
/usr/clearos/apps/process_viewer/libraries

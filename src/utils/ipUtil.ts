import { Netmask } from "netmask";
export class ipUtil {
    
    private getCIDR(netmask: string): string {
        let subnetPrefixMap = new Map();
        subnetPrefixMap.set("255.255.255.255", "32");
        subnetPrefixMap.set("255.255.255.254", "31");
        subnetPrefixMap.set("255.255.255.252", "30");
        subnetPrefixMap.set("255.255.255.248", "29");
        subnetPrefixMap.set("255.255.255.240", "28");
        subnetPrefixMap.set("255.255.255.224", "27");
        subnetPrefixMap.set("255.255.255.192", "26");
        subnetPrefixMap.set("255.255.255.128", "25");
        subnetPrefixMap.set("255.255.255.0", "24");
        subnetPrefixMap.set("255.255.254.0", "23");
        subnetPrefixMap.set("255.255.252.0", "22");
        subnetPrefixMap.set("255.255.248.0", "21");
        subnetPrefixMap.set("255.255.240.0", "32");
        subnetPrefixMap.set("255.255.224.0", "19");
        subnetPrefixMap.set("255.255.192.0", "18");
        subnetPrefixMap.set("255.255.128.0", "17");
        subnetPrefixMap.set("255.255.0.0", "16");
        subnetPrefixMap.set("255.254.0.0", "15");
        subnetPrefixMap.set("255.252.0.0", "14");
        subnetPrefixMap.set("255.248.0.0", "13");
        subnetPrefixMap.set("255.240.0.0", "12");
        subnetPrefixMap.set("255.224.0.0", "11");
        subnetPrefixMap.set("255.192.0.0", "10");
        subnetPrefixMap.set("255.128.0.0", "9");
        subnetPrefixMap.set("255.0.0.0", "8");
        subnetPrefixMap.set("254.0.0.0", "7");
        subnetPrefixMap.set("252.0.0.0", "6");
        subnetPrefixMap.set("248.0.0.0", "5");
        subnetPrefixMap.set("240.0.0.0", "4");
        subnetPrefixMap.set("224.0.0.0", "3");
        subnetPrefixMap.set("192.0.0.0", "2");
        subnetPrefixMap.set("128.0.0.0", "1");

        return subnetPrefixMap.get(netmask);
    }

    private getSubnetIP(netmask: string): string {
        return "192.168.1.0/"+this.getCIDR(netmask);
    }

    public isIpInSubnet(ipAddress: string, subnetMask: string) : boolean{
        let subnetMaskCIDR = this.getSubnetIP(subnetMask);
        console.log("subnet mask cidr "+subnetMaskCIDR);
        let block = new Netmask(subnetMaskCIDR);
        return block.contains(ipAddress);
    }

}